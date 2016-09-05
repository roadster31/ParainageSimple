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
                ParainageSimple::TYPE_PARAINAGE,
                'choice',
                [
                    'constraints' =>  [ new NotBlank() ],
                    'choices' => array(
                        ParainageSimple::TYPE_POURCENTAGE => $this->translator->trans("Somme", [], ParainageSimple::DOMAIN_NAME),
                        ParainageSimple::TYPE_SOMME => $this->translator->trans("Pourcentage", [], ParainageSimple::DOMAIN_NAME),
                    ),
                    
                    'label' => $this->translator->trans('Type de code promotion', [], ParainageSimple::DOMAIN_NAME),
                    'label_attr' => [
                        'help' =>  $this->translator->trans('Chosiissez le type de code promotion à générer', [], ParainageSimple::DOMAIN_NAME)
                    ]
                ]
            )
            ->add(
                ParainageSimple::VALEUR_REMISE_PARRAIN,
                'text',
                [
                    'constraints' =>  [ new NotBlank(), new GreaterThan(['value' => 0]) ],
                    'label' => $this->translator->trans('Montant du code promotion offert au parain', [], ParainageSimple::DOMAIN_NAME),
                    'label_attr' => [
                        'help' => $this->translator->trans('Indiquez un montant ou un pourcentage, selon le type de code promo', [], ParainageSimple::DOMAIN_NAME)
                    ]
                ]
            )
            ->add(
                ParainageSimple::MONTANT_ACHAT_MINIMUM,
                'text',
                [
                    'constraints' =>  [ new NotBlank(), new GreaterThanOrEqual(['value' => 0]) ],
                    'label' => $this->translator->trans('Montant d\'achat minimum du parrain en Euros', [], ParainageSimple::DOMAIN_NAME),
                    'label_attr' => [
                        'help' => $this->translator->trans('Le montant minimum de la commande du parrain pour utiliser son code promo', [], ParainageSimple::DOMAIN_NAME)
                    ]
                ]
            )
            ->add(
                ParainageSimple::VALEUR_REMISE_FILLEUL,
                'text',
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
