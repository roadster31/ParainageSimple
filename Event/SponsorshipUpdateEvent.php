<?php
/**
 * Created by PhpStorm.
 * User: audreymartel
 * Date: 16/07/2018
 * Time: 14:58
 */

namespace ParainageSimple\Event;


class SponsorshipUpdateEvent extends SponsorshipEvent
{
    /** @var int */
    private $beneficiaryId;

    public function setBeneficiaryId($id)
    {
        $this->beneficiaryId = $id;
    }

    /**
     * @return int
     */
    public function getBeneficiaryId()
    {
        return $this->beneficiaryId;
    }


}