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

namespace ParainageSimple\Loop;

use ParainageSimple\ParainageSimpleConfiguration;
use ParainageSimple\ParainageSimpleHelper;
use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

class InfoParainageLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection();
    }

    public function buildArray()
    {
        return [1];
    }

    public function parseResults(LoopResult $loopResult)
    {
        $loopResultRow = (new LoopResultRow())
            ->set('LABEL_PROMOTION', ParainageSimpleHelper::getDiscountLabel(
                ParainageSimpleConfiguration::getSponsorshipType(),
                ParainageSimpleConfiguration::getSponsorDiscountAmount(),
                ParainageSimpleConfiguration::getMinimumCartAmount()
            ))
            ->set('TYPE_PARRAINAGE', ParainageSimpleConfiguration::getSponsorshipType())
            ->set('VALEUR_REMISE_FILLEUL', ParainageSimpleConfiguration::getBeneficiaryDiscountAmount())
            ->set('VALEUR_REMISE_PARRAIN', ParainageSimpleConfiguration::getSponsorDiscountAmount())
            ->set('MONTANT_ACHAT_MINIMUM', ParainageSimpleConfiguration::getMinimumCartAmount())
        ;
        
        $loopResult->addRow($loopResultRow);

        return $loopResult;
    }
}
