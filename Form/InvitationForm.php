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

namespace ParainageSimple\Form;

use ParainageSimple\ParainageSimple;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Form\BaseForm;

class InvitationForm extends BaseForm
{
    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                'email',
                EmailType::class,
                [
                    'constraints' =>  [ new NotBlank(), new Email() ],
                    'label' => $this->translator->trans('Indiquez l\'email de votre ami', [], ParainageSimple::DOMAIN_NAME),
                    'label_attr' => [
                        'help' =>  $this->translator->trans('Il s\'agit de l\'email de la personne que vous souhiatez inviter.', [], ParainageSimple::DOMAIN_NAME)
                    ]
                ]
            )
        ;
    }
}
