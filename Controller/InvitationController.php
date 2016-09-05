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

use ParainageSimple\ParainageSimple;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Model\ConfigQuery;
use Thelia\Model\Customer;
use Thelia\Tools\URL;

class InvitationController extends BaseFrontController
{
    public function invitation()
    {
        $invitationForm = $this->createForm('parainagesimple.form.invitation');
    
        try {
            $form = $this->validateForm($invitationForm, "POST");
        
            // Get the form field values
            $data = $form->getData();
        
            /** @var Customer $parrain */
            if (null !== $parrain = $this->getSession()->getCustomerUser()) {
                if (strtolower($data['email']) == strtolower($parrain->getEmail())) {
                    throw new \Exception("Vous ne pouvez pas être votre propre parrain.");
                }
                
                $this->getMailer()->sendEmailMessage(
                    ParainageSimple::MAIL_INVITATION_FILLEUL,
                    [ ConfigQuery::getStoreEmail() => ConfigQuery::getStoreName() ],
                    [ $data['email'] => '' ],
                    [
                        'id_parrain' => $parrain->getId(),
                        'label_promo' => ParainageSimple::getlabelPromo(
                            ParainageSimple::getConfigValue(ParainageSimple::TYPE_PARAINAGE),
                            ParainageSimple::getConfigValue(ParainageSimple::VALEUR_REMISE_PARRAIN),
                            ParainageSimple::getConfigValue(ParainageSimple::MONTANT_ACHAT_MINIMUM)
                        ),
                        'remise_filleul' => ParainageSimple::getConfigValue(ParainageSimple::VALEUR_REMISE_FILLEUL)
                    ]
                );
                
                return $this->generateSuccessRedirect($invitationForm);
            } else {
                throw new \Exception("Vous devez être connecté pour parrainer un ami");
            }
        } catch (\Exception $ex) {
            $errorMessage = $ex->getMessage();
            $invitationForm->setErrorMessage($errorMessage);
        }
    
        $this->getParserContext()
            ->setGeneralError($errorMessage)
            ->addForm($invitationForm);
        
        return $this->generateErrorRedirect($invitationForm);
    }
}
