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

use ParainageSimple\ParainageSimple;
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
            ->set('LABEL_PROMOTION', ParainageSimple::getlabelPromo(
                ParainageSimple::getConfigValue(ParainageSimple::TYPE_PARAINAGE),
                ParainageSimple::getConfigValue(ParainageSimple::VALEUR_REMISE_PARRAIN),
                ParainageSimple::getConfigValue(ParainageSimple::MONTANT_ACHAT_MINIMUM)
            ))
            ->set('TYPE_PARRAINAGE', ParainageSimple::getConfigValue(ParainageSimple::TYPE_PARAINAGE))
            ->set('VALEUR_REMISE_FILLEUL', ParainageSimple::getConfigValue(ParainageSimple::VALEUR_REMISE_FILLEUL))
            ->set('VALEUR_REMISE_PARRAIN', ParainageSimple::getConfigValue(ParainageSimple::VALEUR_REMISE_PARRAIN))
            ->set('MONTANT_ACHAT_MINIMUM', ParainageSimple::getConfigValue(ParainageSimple::MONTANT_ACHAT_MINIMUM))
        ;
        
        $loopResult->addRow($loopResultRow);

        return $loopResult;
    }
}
