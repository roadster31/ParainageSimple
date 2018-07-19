<?php
/**
 * Created by PhpStorm.
 * User: audreymartel
 * Date: 18/07/2018
 * Time: 17:11
 */

namespace ParainageSimple\Form;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

class CustomInvitationForm extends BaseForm
{
    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                'firstname',
                TextType::class,
                [
                    'label' => Translator::getInstance()->trans('Firstname'),
                    'label_attr' => ['for' => 'firstname']
                ]
            )
            ->add(
                'lastname',
                TextType::class,
                [
                    'label' => Translator::getInstance()->trans('Lastname'),
                    'label_attr' => ['for' => 'lastname']
                ]
            )
            ->add(
                'message',
                TextareaType::class,
                [
                    'attr' => ['class' => 'tinymce'],
                    'data' => $this->getRequest()->getSession()->get("parainage_simple_invitation_message"),
                    'label' => Translator::getInstance()->trans('Message'),
                    'label_attr' => ['for' => 'message']
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'constraints' => [new NotBlank(), new Email(),],
                    'label' => Translator::getInstance()->trans('Beneficiary Email addresse'),
                    'label_attr' => [
                        'help' => Translator::getInstance()->trans('This is the email of the person you want to invite'),
                    ]
                ]
            );
    }
}
