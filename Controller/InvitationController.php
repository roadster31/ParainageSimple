<?php
/*************************************************************************************/
/*      Copyright (c) Franck Allimant, CQFDev                                        */
/*      email : thelia@cqfdev.fr                                                     */
/*      web : http://www.cqfdev.fr                                                   */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE      */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

/**
 * Created by Franck Allimant, CQFDev <franck@cqfdev.fr>
 * Date: 02/09/2016 16:43
 */

namespace ParainageSimple\Controller;

use ParainageSimple\Action\Sponsorship;
use ParainageSimple\Event\SponsorshipCreateEvent;
use ParainageSimple\Event\SponsorshipDeleteEvent;
use ParainageSimple\Model\SponsorshipCode;
use ParainageSimple\Model\SponsorshipStatus;
use ParainageSimple\Model\SponsorshipStatusQuery;
use ParainageSimple\ParainageSimpleConfiguration;
use ParainageSimple\ParainageSimpleHelper;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\Translation\Translator;
use Thelia\Log\Tlog;
use Thelia\Model\ConfigQuery;
use Thelia\Model\Customer;
use Thelia\Tools\URL;

class InvitationController extends BaseFrontController
{
    public function invitationWithCode() {
        $invitationForm = $this->createForm('parainagesimple.form.invitation.code');
        $errorMessage = null;
        try {
            $form = $this->validateForm($invitationForm, "POST");
            $data = $form->getData();
            $this->senInvitationEmail($data);
        } catch (\Exception $ex) {
            $errorMessage = $ex->getMessage();
        }
        $invitationForm->setErrorMessage($errorMessage);
        $this->getParserContext()
            ->setGeneralError($errorMessage)
            ->addForm($invitationForm);

        if (empty($errorMessage) && null != $successUrl = $invitationForm->getSuccessUrl()) {
            $response = $this->generateRedirect(
                URL::getInstance()->absoluteUrl($successUrl)
            );

            return $response;
        }
        return $this->generateErrorRedirect($invitationForm);
    }

    public function invitation()
    {
        $invitationForm = $this->createForm('parainagesimple.form.invitation');
        $errorMessage = null;
        try {
            $form = $this->validateForm($invitationForm, "POST");
            $data = $form->getData();
            $this->senInvitationEmail($data);
        } catch (\Exception $ex) {
            $errorMessage = $ex->getMessage();
        }
        $invitationForm->setErrorMessage($errorMessage);
        $this->getParserContext()
            ->setGeneralError($errorMessage)
            ->addForm($invitationForm);
        
        return $this->generateErrorRedirect($invitationForm);
    }

    /**
     * @param $data
     * @throws \Exception
     */
    private function senInvitationEmail($data) {
        /** @var Customer $sponsor */
        $sponsor = $this->getSession()->getCustomerUser();
        if (null === $sponsor) {
            throw new \Exception(Translator::getInstance()->trans('You must be connected to invite a friend'));
        }
        if (stripos($data['email'], $sponsor->getEmail())) {
            throw new \Exception(Translator::getInstance()->trans('You cannot be your own sponsor'));
        }

        $this->getRequest()->getSession()->set("parainage_simple_invitation_message", $data['message']);

        $invitationWithCode = ParainageSimpleConfiguration::useInvitationCode();

        $code = null;

        if ($invitationWithCode) {
            $code = SponsorshipCode::generateRandomCode();
            $sponsorshipEvent = new SponsorshipCreateEvent();
            /** @noinspection PhpParamsInspection */
            $sponsorshipEvent->setStatus(SponsorshipStatusQuery::create()->findOneByCode(SponsorshipStatus::CODE_INVITATION_SENT));
            $sponsorshipEvent->setFirstname($data['firstname']);
            $sponsorshipEvent->setLastname($data['lastname']);
            $sponsorshipEvent->setEmail($data['email']);
            $sponsorshipEvent->setCode($code);
            $sponsorshipEvent->setSponsorId($sponsor->getId());
            $this->getDispatcher()->dispatch(Sponsorship::SPONSORSHIP_CREATE, $sponsorshipEvent);
        }

        try {
            $this->getMailer()->sendEmailMessage(
                ParainageSimpleConfiguration::getMessageCodeForInvitation(),
                [ ConfigQuery::getStoreEmail() => ConfigQuery::getStoreName() ],
                [ $data['email'] => '' ],
                [
                    'sponsor_id' => $sponsor->getId(),
                    'sponsor_code' => $code,
                    'beneficiary_firstname' => $data['firstname'],
                    'beneficiary_lastname' => $data['lastname'],
                    'custom_message' => $data['message'],
                    'label_promo' => ParainageSimpleHelper::getDiscountLabel(
                        ParainageSimpleConfiguration::getSponsorshipType(),
                        ParainageSimpleConfiguration::getSponsorDiscountAmount(),
                        ParainageSimpleConfiguration::getMinimumCartAmount()
                    ),
                    'beneficiary_discount' => ParainageSimpleConfiguration::getBeneficiaryDiscountAmount()
                ]
            );
        } catch (\Exception $e) {
            Tlog::getInstance()->error($e->getMessage());
            if ($code !== null) {
                $sponsorshipEvent = new SponsorshipDeleteEvent();
                $sponsorshipEvent->setCode($code);
                $this->getDispatcher()->dispatch(Sponsorship::SPONSORSHIP_DELETE, $sponsorshipEvent);
            }
            throw new \Exception(Translator::getInstance()->trans('Fail to send the email'));
        }
    }
}
