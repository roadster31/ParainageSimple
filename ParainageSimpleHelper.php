<?php
/**
 * Created by PhpStorm.
 * User: audreymartel
 * Date: 18/07/2018
 * Time: 14:20
 */

namespace ParainageSimple;


use ParainageSimple\Model\SponsorshipDiscountType;
use Thelia\Model\Currency;

class ParainageSimpleHelper
{
    public static function getDiscountLabel($type, $valeur, $mini)
    {
        $labelCurrency = Currency::getDefaultCurrency()->getSymbol();

        $labelPromo = $valeur . ($type == SponsorshipDiscountType::TYPE_PERCENT ? '%' : ' ' . $labelCurrency);

        if ($mini > 0) {
            $labelPromo .= " à partir de ".sprintf("%1.2f", $mini)." $labelCurrency d'achat";
        }

        return $labelPromo;
    }
}