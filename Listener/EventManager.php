<?php
/*************************************************************************************/
/*                                                                                   */
/*      This file is not free software                                               */
/*                                                                                   */
/*      Copyright (c) Franck Allimant, CQFDev                                        */
/*      email : thelia@cqfdev.fr                                                     */
/*      web : http://www.cqfdev.fr                                                   */
/*                                                                                   */
/*************************************************************************************/

namespace ParainageSimple\Listener;

use ParainageSimple\Action\Sponsorship;
use ParainageSimple\Event\SponsorshipUpdateEvent;
use ParainageSimple\Model\SponsorshipDiscountType;
use ParainageSimple\Model\SponsorshipQuery;
use ParainageSimple\Model\SponsorshipStatus;
use ParainageSimple\Model\SponsorshipStatusQuery;
use ParainageSimple\ParainageSimple;
use ParainageSimple\ParainageSimpleConfiguration;
use ParainageSimple\ParainageSimpleHelper;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Thelia\Action\BaseAction;
use Thelia\Condition\ConditionCollection;
use Thelia\Condition\Implementation\ConditionInterface;
use Thelia\Condition\Implementation\MatchForTotalAmount;
use Thelia\Condition\Operators;
use Thelia\Core\Event\Coupon\CouponConsumeEvent;
use Thelia\Core\Event\Coupon\CouponCreateOrUpdateEvent;
use Thelia\Core\Event\Customer\CustomerCreateOrUpdateEvent;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\TheliaFormEvent;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Translation\Translator;
use Thelia\Log\Tlog;
use Thelia\Mailer\MailerFactory;
use Thelia\Model\CouponQuery;
use Thelia\Model\Currency;
use Thelia\Model\Customer;
use Thelia\Model\CustomerQuery;
use Thelia\Model\OrderQuery;
use Thelia\Model\OrderStatusQuery;

class EventManager extends BaseAction implements EventSubscriberInterface
{
    /**  @var MailerFactory */
    protected $mailer;

    /** @var EventDispatcherInterface */
    protected $dispatcher;

    /** @var Request $request */
    protected $request;

    /** @var ConditionInterface $couponCondition */
    protected $couponCondition;

    const FIELD_NAME_SPONSOR_EMAIL = 'email_parrain';

    const FIELD_NAME_SPONSOR_CODE = 'sponsor_code';

    public function __construct(Request $request, MailerFactory $mailer, EventDispatcherInterface $dispatcher, ConditionInterface $couponCondition)
    {
        $this->request = $request;
        $this->mailer = $mailer;
        $this->dispatcher = $dispatcher;
        $this->couponCondition = $couponCondition;
    }

    /**
     * @param OrderEvent $event
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Exception
     */
    public function orderStatusUpdate(OrderEvent $event)
    {
        // Si la commande d'un filleul a été payée, on crée le coupon pour le parrain, et on lui envoie le mail
        if ($event->getOrder()->isPaid(true)) {
            $filleul = $event->getOrder()->getCustomer();

            // Récupérer le parrain
            if (null !== $parrain = CustomerQuery::create()->findPk(intval($filleul->getSponsor()))) {
                // Créer le code promo
                $code = sprintf('PAR%dP%d', $filleul->getId(), $parrain->getId());

                /** @noinspection PhpParamsInspection */
                if (null === CouponQuery::create()->findOneByCode($code)) {
                    // Le montant / pourcentage à déduire

                    $discountAmount = ParainageSimpleConfiguration::getSponsorDiscountAmount();
                    if (ParainageSimpleConfiguration::getSponsorshipType() == SponsorshipDiscountType::TYPE_PERCENT) {
                        $couponServiceId = 'thelia.coupon.type.remove_x_percent';
                        $effects = [ 'percentage' => $discountAmount];
                    } else {
                        $couponServiceId = 'thelia.coupon.type.remove_x_amount';
                        $effects = [ 'amount' =>  $discountAmount];
                    }

                    // Expiration dans 1 an
                    $dateExpiration = (new \DateTime())->add(new \DateInterval('P1Y'));

                    $couponEvent = new CouponCreateOrUpdateEvent(
                        $code, // Code
                        $couponServiceId, // $serviceId
                        sprintf(
                            "Parrainage de %s %s (%s) par %s %s (%s)",
                            $filleul->getLastname(),
                            $filleul->getFirstname(),
                            $filleul->getRef(),
                            $parrain->getLastname(),
                            $parrain->getFirstname(),
                            $parrain->getRef()
                        ), // $title
                        $effects, // $effects
                        '', // $shortDescription
                        '', // $description
                        true, // $isEnabled
                        $dateExpiration, // $expirationDate
                        false, // $isAvailableOnSpecialOffers
                        false, // $isCumulative
                        false, // $isRemovingPostage,
                        1, // $maxUsage,
                        $parrain->getCustomerLang()->getLocale(), // $locale,
                        [], // $freeShippingForCountries,
                        [], // $freeShippingForMethods,
                        1 // $perCustomerUsageCount,
                    );

                    $this->dispatcher->dispatch(TheliaEvents::COUPON_CREATE, $couponEvent);

                    if ($couponEvent->getCouponModel() !== null) {
                        // Mise en place de la condition sur le total du panier
                        $conditions = new ConditionCollection();

                        // La condition est MatchForTotalAmount, la configurer.
                        $conditions[] = $this->couponCondition->setValidatorsFromForm(
                            [
                                MatchForTotalAmount::CART_TOTAL => Operators::SUPERIOR_OR_EQUAL,
                                MatchForTotalAmount::CART_CURRENCY => Operators::EQUAL
                            ],
                            [
                                MatchForTotalAmount::CART_TOTAL => ParainageSimpleConfiguration::getMinimumCartAmount(),
                                MatchForTotalAmount::CART_CURRENCY => Currency::getDefaultCurrency()->getCode()
                            ]
                        );

                        $couponEvent->setConditions($conditions);

                        $this->dispatcher->dispatch(TheliaEvents::COUPON_CONDITION_UPDATE, $couponEvent);

                        // Envoyer le mail au client
                        $this->mailer->sendEmailToCustomer(
                            ParainageSimpleConfiguration::MESSAGE_NAME_MAIL_SPONSOR,
                            $parrain,
                            [
                                'beneficiary_id' => $filleul->getId(),
                                'sponsor_id' => $parrain->getId(),
                                'label_promo' => ParainageSimpleHelper::getDiscountLabel(
                                    ParainageSimpleConfiguration::getSponsorshipType(),
                                    ParainageSimpleConfiguration::getSponsorDiscountAmount(),
                                    ParainageSimpleConfiguration::getMinimumCartAmount()
                                ),
                                'code_promo' => $code
                            ]
                        );
                    }
                }
            }
        }
    }

    /**
     * @throws PropelException
     * @throws \Exception
     */
    public function applyBeneficiaryDiscount()
    {
        $beneficiaryDiscountAmount = ParainageSimpleConfiguration::getBeneficiaryDiscountAmount();

        if ($beneficiaryDiscountAmount <= 0) {
            return;
        }

        /** @var Customer $beneficiary */
        $beneficiary = $this->request->getSession()->getCustomerUser();
        $sponsor = CustomerQuery::create()->findPk(intval($beneficiary->getSponsor()));

        // Si le client est parrainé, et que c'est sa première commande
        if ($sponsor === null) {
            return;
        }

        // Compter le nombre de commandes non annulées de ce client
        $orderCount = OrderQuery::create()
            ->filterByCustomerId($beneficiary->getId())
            ->filterByOrderStatus(OrderStatusQuery::getCancelledStatus(), Criteria::NOT_EQUAL)
            ->count();

        if ($orderCount > 0) {
            return;
        }

        // Creer un coupon du montant de la remise, et le placer dans la commande.
        $code = sprintf('PARRAINAGE%dP%d', $beneficiary->getId(), $sponsor->getId());

        /** @noinspection PhpParamsInspection */
        $coupon = CouponQuery::create()->findOneByCode($code);
        if (null === $coupon) {
            // Le pourcentage à déduire
            $effects = ['percentage' => $beneficiaryDiscountAmount];

            // Le type de remise
            $couponServiceId = 'thelia.coupon.type.remove_x_percent';

            // Expiration dans 1 an
            $dateExpiration = (new \DateTime())->add(new \DateInterval('P1Y'));

            $couponEvent = new CouponCreateOrUpdateEvent(
                $code, // Code
                $couponServiceId, // $serviceId
                sprintf(
                    "Remise 1ère commande suite au parrainage de %s %s (%s) par %s %s (%s)",
                    $beneficiary->getLastname(),
                    $beneficiary->getFirstname(),
                    $beneficiary->getRef(),
                    $sponsor->getLastname(),
                    $sponsor->getFirstname(),
                    $sponsor->getRef()
                ), // $title
                $effects, // $effects
                '', // $shortDescription
                '', // $description
                true, // $isEnabled
                $dateExpiration, // $expirationDate
                false, // $isAvailableOnSpecialOffers
                false, // $isCumulative
                false, // $isRemovingPostage,
                1, // $maxUsage,
                $sponsor->getCustomerLang()->getLocale(), // $locale,
                [], // $freeShippingForCountries,
                [], // $freeShippingForMethods,
                1 // $perCustomerUsageCount,
            );

            $this->dispatcher->dispatch(TheliaEvents::COUPON_CREATE, $couponEvent);

            $coupon = $couponEvent->getCouponModel();
        }

        if (null !== $coupon) {
            // Consommer notre coupon
            $couponConsumeEvent = new CouponConsumeEvent($code);

            // Dispatch Event to the Action
            $this->dispatcher->dispatch(TheliaEvents::COUPON_CONSUME, $couponConsumeEvent);
        }
    }

    public function addSponsorCodeField(TheliaFormEvent $event)
    {
        $event->getForm()->getFormBuilder()->add(
            self::FIELD_NAME_SPONSOR_CODE,
            TextType::class,
            [
                'constraints' => [
                    new Callback([ 'methods' => [[ $this, 'existenceSponsorCode' ]]])
                ],
                'required' => false,
                'label' => Translator::getInstance()->trans(
                    'Sponsor code',
                    [],
                    ParainageSimple::DOMAIN_NAME
                ),
                'label_attr'  => [
                    'help' => Translator::getInstance()->trans(
                        "If you received an invitation, thank to add the sponsor code here.",
                        [],
                        ParainageSimple::DOMAIN_NAME
                    )
                ]
            ]
        );
    }

    public function addSponsorEmailField(TheliaFormEvent $event)
    {
        $event->getForm()->getFormBuilder()->add(
            self::FIELD_NAME_SPONSOR_EMAIL,
            EmailType::class,
            [
                'constraints' => [
                    new Callback([ 'methods' => [[ $this, 'existenceSponsorEmail' ]]])
                ],
                'required' => false,
                'label' => Translator::getInstance()->trans(
                    'Adresse e-mail de votre parrain',
                    [],
                    ParainageSimple::DOMAIN_NAME
                ),
                'label_attr'  => [
                    'help' => Translator::getInstance()->trans(
                        "Si vous avez été parrainé, merci d'indiquez ici l'adresse email de votre parrain.",
                        [],
                        ParainageSimple::DOMAIN_NAME
                    )
                ]
            ]
        );
    }

    public function addSponsorField(TheliaFormEvent $event)
    {
        if (ParainageSimpleConfiguration::useInvitationCode()) {
            $this->addSponsorCodeField($event);
        } else {
            $this->addSponsorEmailField($event);
        }
    }

    public function existenceSponsorCode($value, ExecutionContextInterface $context)
    {
        $this->request->getSession()->set(self::FIELD_NAME_SPONSOR_CODE, null);

        /** @noinspection PhpParamsInspection */
        $sponsorship = SponsorshipQuery::create()->findOneByCode($value);
        if (null === $sponsorship) {
            $context->addViolation(
                Translator::getInstance()->trans(
                    "Non existant sponsor code. Please check the value.",
                    [ ],
                    ParainageSimple::DOMAIN_NAME
                )
            );
            return;
        }
        /** @noinspection PhpParamsInspection */
        $sponsorshipStatus = SponsorshipStatusQuery::create()->findOneById($sponsorship->getStatus());
        if ($sponsorshipStatus->getCode() !== SponsorshipStatus::CODE_INVITATION_SENT) {
            $context->addViolation(
                Translator::getInstance()->trans(
                    "Sponsor code already in use. Please check the value.",
                    [ ],
                    ParainageSimple::DOMAIN_NAME
                ));
            return;
        }
        $this->request->getSession()->set(self::FIELD_NAME_SPONSOR_CODE, $value);
    }

    public function existenceSponsorEmail($value, ExecutionContextInterface $context)
    {
        $this->request->getSession()->set(self::FIELD_NAME_SPONSOR_EMAIL, null);

        if (null === CustomerQuery::create()->findOneByEmail($value)) {
            $context->addViolation(
                Translator::getInstance()->trans(
                    "Nous n'avons pas trouvé l'adresse e-mail de votre parrain parmi nos client. Merci de vérifier que cette adresse est bien correcte.",
                    [ ],
                    ParainageSimple::DOMAIN_NAME
                )
            );
        } else {
            $this->request->getSession()->set(self::FIELD_NAME_SPONSOR_EMAIL, $value);
        }
    }


    public function processSponsorCodeField(CustomerCreateOrUpdateEvent $event)
    {

        if ($event->hasCustomer()) {
            $sponsorCode = $this->request->getSession()->get(self::FIELD_NAME_SPONSOR_CODE, null);
            if (empty($sponsorCode)) {
                return;
            }
            $sponsorship = SponsorshipQuery::create()->findOneByCode($sponsorCode);
            if ($sponsorship === null) {
                return;
            }
            /** @noinspection PhpParamsInspection */
            $sponsor = CustomerQuery::create()->findOneById($sponsorship->getSponsorId());
            if (null !== $sponsor) {
                try {
                    $event
                        ->getCustomer()
                        ->setSponsor($sponsor->getId())
                        ->save();

                    $sponsorshipUpdateEvent = new SponsorshipUpdateEvent();
                    /** @noinspection PhpParamsInspection */
                    $sponsorshipUpdateEvent->setStatus(SponsorshipStatusQuery::create()->findOneByCode(SponsorshipStatus::CODE_INVITATION_ACCEPTED));
                    $sponsorshipUpdateEvent->setCode($sponsorCode);
                    $sponsorshipUpdateEvent->setBeneficiaryId($event->getCustomer()->getId());
                    $this->dispatcher->dispatch(Sponsorship::SPONSORSHIP_UPDATE, $sponsorshipUpdateEvent);
                } catch (PropelException $e) {
                    Tlog::getInstance()->error($e->getMessage());
                }
            }
        }
        $this->request->getSession()->set(self::FIELD_NAME_SPONSOR_CODE, null);
    }

    public function processSponsorEmailField(CustomerCreateOrUpdateEvent $event)
    {
        if ($event->hasCustomer()) {
            $emailParrain = $this->request->getSession()->get(self::FIELD_NAME_SPONSOR_EMAIL, null);
            if (empty($emailParrain)) {
                return;
            }
            $sponsor = CustomerQuery::create()->findOneByEmail($emailParrain);
            if (null !== $sponsor) {
                try {
                    $event
                        ->getCustomer()
                        ->setSponsor($sponsor->getId())
                        ->save();
                } catch (PropelException $e) {
                    Tlog::getInstance()->error($e->getMessage());
                }
            }
        }
        $this->request->getSession()->set(self::FIELD_NAME_SPONSOR_EMAIL, null);
    }

    public function processSponsorField(CustomerCreateOrUpdateEvent $event)
    {
        if (ParainageSimpleConfiguration::useInvitationCode()) {
            $this->processSponsorCodeField($event);
        } else {
            $this->processSponsorEmailField($event);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::ORDER_UPDATE_STATUS => [ 'orderStatusUpdate', 10 ],
            TheliaEvents::FORM_BEFORE_BUILD . ".thelia_customer_create" => ['addSponsorField', 128],
            TheliaEvents::CUSTOMER_CREATEACCOUNT => [ 'processSponsorField', 10 ],
            TheliaEvents::ORDER_SET_POSTAGE => [ 'applyBeneficiaryDiscount', 10 ]
        ];
    }
}
