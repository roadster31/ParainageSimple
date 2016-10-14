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

use ParainageSimple\ParainageSimple;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
use Thelia\Mailer\MailerFactory;
use Thelia\Model\Base\CustomerQuery;
use Thelia\Model\CouponQuery;
use Thelia\Model\Currency;
use Thelia\Model\Customer;
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
    
    public function __construct(Request $request, MailerFactory $mailer, EventDispatcherInterface $dispatcher, ConditionInterface $couponCondition)
    {
        $this->request = $request;
        $this->mailer = $mailer;
        $this->dispatcher = $dispatcher;
        $this->couponCondition = $couponCondition;
    }

    public function orderStatusUpdate(OrderEvent $event)
    {
        // Si la commande d'un filleul a été payée, on crée le coupon pour le parrain, et on lui envoie le mail
        if ($event->getOrder()->isPaid(true)) {
            $filleul = $event->getOrder()->getCustomer();
            
            // Récupérer le parrain
            if (null !== $parrain = CustomerQuery::create()->findPk(intval($filleul->getSponsor()))) {
                // Créer le code promo
                $code = sprintf('PAR%dP%d', $filleul->getId(), $parrain->getId());
                
                if (null === CouponQuery::create()->findOneByCode($code)) {
                    // Le montant / pourcentage à déduire
                    if (ParainageSimple::getConfigValue(ParainageSimple::TYPE_PARAINAGE) == ParainageSimple::TYPE_POURCENTAGE) {
                        $couponServiceId = 'thelia.coupon.type.remove_x_percent';
                        $effects = [ 'percentage' => ParainageSimple::getConfigValue(ParainageSimple::VALEUR_REMISE_PARRAIN) ];
                    } else {
                        $couponServiceId = 'thelia.coupon.type.remove_x_amount';
                        $effects = [ 'amount' => ParainageSimple::getConfigValue(ParainageSimple::VALEUR_REMISE_PARRAIN) ];
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
                                MatchForTotalAmount::CART_TOTAL => ParainageSimple::getConfigValue(ParainageSimple::MONTANT_ACHAT_MINIMUM),
                                MatchForTotalAmount::CART_CURRENCY => Currency::getDefaultCurrency()->getCode()
                            ]
                        );
    
                        $couponEvent->setConditions($conditions);
    
                        $this->dispatcher->dispatch(TheliaEvents::COUPON_CONDITION_UPDATE, $couponEvent);
    
                        // Envoyer le mail au client
                        $this->mailer->sendEmailToCustomer(
                            ParainageSimple::MAIL_PARRAIN,
                            $parrain,
                            [
                                'id_filleul' => $filleul->getId(),
                                'id_parrain' => $parrain->getId(),
                                'label_promo' => ParainageSimple::getlabelPromo(
                                    ParainageSimple::getConfigValue(ParainageSimple::TYPE_PARAINAGE),
                                    ParainageSimple::getConfigValue(ParainageSimple::VALEUR_REMISE_PARRAIN),
                                    ParainageSimple::getConfigValue(ParainageSimple::MONTANT_ACHAT_MINIMUM)
                                ),
                                'code_promo' => $code
                            ]
                        );
                    }
                }
            }
        }
    }
    
    public function attribuerRemiseAuFilleul()
    {
        $valeurRemiseFilleul = ParainageSimple::getConfigValue(ParainageSimple::VALEUR_REMISE_FILLEUL);
        
        if ($valeurRemiseFilleul > 0) {
            /** @var Customer $filleul */
            $filleul = $this->request->getSession()->getCustomerUser();
    
            // Si le client est parrainé, et que c'est sa première commande
            if (null !== $parrain = CustomerQuery::create()->findPk(intval($filleul->getSponsor()))) {
                // Compter le nombre de commandes non annulées de ce client
                $orderCount = OrderQuery::create()
                    ->filterByCustomerId($filleul->getId())
                    ->filterByOrderStatus(OrderStatusQuery::getCancelledStatus(), Criteria::NOT_EQUAL)
                    ->count();
        
                if ($orderCount == 0) {
                    // Creer un coupon du montant de la remise, et le placer dans la commande.
                    $code = sprintf('PARRAINAGE%dP%d', $filleul->getId(), $parrain->getId());
            
                    if (null === $coupon = CouponQuery::create()->findOneByCode($code)) {
                        // Le pourcentage à déduire
                        $effects = ['percentage' => $valeurRemiseFilleul];
                
                        // Le type de remise
                        $couponServiceId = 'thelia.coupon.type.remove_x_percent';
                
                        // Expiration dans 1 an
                        $dateExpiration = (new \DateTime())->add(new \DateInterval('P1Y'));
                
                        $couponEvent = new CouponCreateOrUpdateEvent(
                            $code, // Code
                            $couponServiceId, // $serviceId
                            sprintf(
                                "Remise 1ère commande suite au parrainage de %s %s (%s) par %s %s (%s)",
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
                
                        $coupon = $couponEvent->getCouponModel();
                    }
            
                    if (null !== $coupon) {
                        // Consommer notre coupon
                        $couponConsumeEvent = new CouponConsumeEvent($code);
                
                        // Dispatch Event to the Action
                        $this->dispatcher->dispatch(TheliaEvents::COUPON_CONSUME, $couponConsumeEvent);
                    }
                }
            }
        }
    }
    
    public function ajouterSaisieParrain(TheliaFormEvent $event)
    {
        $event->getForm()->getFormBuilder()->add(
            'email_parrain',
            "email",
            [
                'constraints' => [
                    new Callback([ 'methods' => [[ $this, 'existenceParrain' ]]])
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
    
    public function existenceParrain($value, ExecutionContextInterface $context)
    {
        $this->request->getSession()->set('email_parrain', null);
        
        if (null === \Thelia\Model\CustomerQuery::create()->findOneByEmail($value)) {
            $context->addViolation(
                Translator::getInstance()->trans(
                    "Nous n'avons pas trouvé l'adresse e-mail de votre parrain parmi nos client. Merci de vérifier que cette adresse est bien correcte.",
                    [ ],
                    ParainageSimple::DOMAIN_NAME
                )
            );
        } else {
            $this->request->getSession()->set('email_parrain', $value);
        }
    }
    
    public function traiterChampParrain(CustomerCreateOrUpdateEvent $event)
    {
        if ($event->hasCustomer()) {
            $emailParrain = $this->request->getSession()->get('email_parrain', null);
            
            if (! empty($emailParrain) && null !== $parrain = CustomerQuery::create()->findOneByEmail($emailParrain)) {
                $event->getCustomer()
                    ->setSponsor($parrain->getId())
                    ->save();
            }
        }
    
        $this->request->getSession()->set('email_parrain', null);
    }
    
    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::ORDER_UPDATE_STATUS => [ 'orderStatusUpdate', 10 ],
            TheliaEvents::FORM_BEFORE_BUILD . ".thelia_customer_create" => ['ajouterSaisieParrain', 128],
            TheliaEvents::CUSTOMER_CREATEACCOUNT => [ 'traiterChampParrain', 10 ],
            TheliaEvents::ORDER_SET_POSTAGE => [ 'attribuerRemiseAuFilleul', 10 ]
        ];
    }
}
