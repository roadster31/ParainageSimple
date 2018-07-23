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

use ParainageSimple\Model\SponsorshipDiscountType;
use ParainageSimple\ParainageSimple;
use ParainageSimple\ParainageSimpleConfiguration;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Form\BaseForm;

/**
 * Class ConfigureParainageSimple
 * @package ParainageSimple\Form
 */
class ConfigurationForm extends BaseForm
{
    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                ParainageSimpleConfiguration::CONFIG_KEY_USE_INVITATION_CODE,
                CheckboxType::class,
                [
                    'constraints' =>  [ new NotBlank() ],
                    'label' => $this->translator->trans('Send sponsor code', [], ParainageSimple::DOMAIN_NAME),
                    'label_attr' => [
                        'help' =>  $this->translator->trans('Check this if you want to follow invitation status', [], ParainageSimple::DOMAIN_NAME)
                    ]
                ]
            )
            ->add(
                ParainageSimpleConfiguration::CONFIG_KEY_SPONSORSHIP_TYPE,
                ChoiceType::class,
                [
                    'constraints' =>  [ new NotBlank() ],
                    'choices' => array(
                        SponsorshipDiscountType::TYPE_AMOUNT => $this->translator->trans("Somme", [], ParainageSimple::DOMAIN_NAME),
                        SponsorshipDiscountType::TYPE_PERCENT => $this->translator->trans("Pourcentage", [], ParainageSimple::DOMAIN_NAME),
                    ),
                    
                    'label' => $this->translator->trans('Type de code promotion', [], ParainageSimple::DOMAIN_NAME),
                    'label_attr' => [
                        'help' =>  $this->translator->trans('Chosiissez le type de code promotion à générer', [], ParainageSimple::DOMAIN_NAME)
                    ]
                ]
            )
            ->add(
                ParainageSimpleConfiguration::CONFIG_KEY_SPONSOR_DISCOUNT_AMOUNT,
                TextType::class,
                [
                    'constraints' =>  [ new NotBlank(), new GreaterThan(['value' => 0]) ],
                    'label' => $this->translator->trans('Montant du code promotion offert au parain', [], ParainageSimple::DOMAIN_NAME),
                    'label_attr' => [
                        'help' => $this->translator->trans('Indiquez un montant ou un pourcentage, selon le type de code promo', [], ParainageSimple::DOMAIN_NAME)
                    ]
                ]
            )
            ->add(
                ParainageSimpleConfiguration::CONFIG_KEY_MINIMUM_CART_AMOUNT,
                TextType::class,
                [
                    'constraints' =>  [ new NotBlank(), new GreaterThanOrEqual(['value' => 0]) ],
                    'label' => $this->translator->trans('Montant d\'achat minimum du parrain en Euros', [], ParainageSimple::DOMAIN_NAME),
                    'label_attr' => [
                        'help' => $this->translator->trans('Le montant minimum de la commande du parrain pour utiliser son code promo', [], ParainageSimple::DOMAIN_NAME)
                    ]
                ]
            )
            ->add(
                ParainageSimpleConfiguration::CONFIG_KEY_BENEFICIARY_DISCOUNT_AMOUNT,
                TextType::class,
                [
                    'constraints' =>  [ new NotBlank(), new GreaterThanOrEqual(['value' => 0]) ],
                    'label' => $this->translator->trans('Remise accordée au filleur, en %', [], ParainageSimple::DOMAIN_NAME),
                    'label_attr' => [
                        'help' => $this->translator->trans(
                            'Le pourcentage de remise accordé au filleul pour sa première commande. Indiquez 0 pour ne pas offrir de remise.',
                            [],
                            ParainageSimple::DOMAIN_NAME
                        )
                    ]
                ]
            )
        ;
    }
}
