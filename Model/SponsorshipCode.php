<?php
/**
 * Created by PhpStorm.
 * User: audreymartel
 * Date: 19/07/2018
 * Time: 14:12
 */

namespace ParainageSimple\Model;


class SponsorshipCode
{
    const CODE_LENGTH = 8;

    static function generateRandomCode()
    {
        $code = sha1(uniqid(mt_rand()));
        $code = base_convert($code, 16, 36);
        $code = strtoupper($code);
        return substr($code, 0, self::CODE_LENGTH);
    }
}