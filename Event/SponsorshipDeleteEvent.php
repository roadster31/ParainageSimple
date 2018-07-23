<?php
/**
 * Created by PhpStorm.
 * User: audreymartel
 * Date: 16/07/2018
 * Time: 15:43
 */

namespace ParainageSimple\Event;


use Thelia\Core\Event\ActionEvent;

class SponsorshipDeleteEvent extends ActionEvent
{
    /** @var integer */
    private $code;

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
}