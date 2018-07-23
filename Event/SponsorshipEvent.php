<?php
/**
 * Created by PhpStorm.
 * User: audreymartel
 * Date: 16/07/2018
 * Time: 14:07
 */

namespace ParainageSimple\Event;


use ParainageSimple\Model\SponsorshipStatus;
use Thelia\Core\Event\ActionEvent;

class SponsorshipEvent extends ActionEvent
{
    /** @var SponsorshipStatus */
    protected $status;

    /** @var string */
    protected $email;

    /** @var string */
    protected $firstname;

    /** @var string */
    protected $lastname;

    /** @var integer */
    private $sponsorId;

    /** @var string */
    private $code;
    /**
     * @return SponsorshipStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param SponsorshipStatus $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getSponsorId()
    {
        return $this->sponsorId;
    }

    /**
     * @param int $sponsorId
     */
    public function setSponsorId($sponsorId)
    {
        $this->sponsorId = $sponsorId;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

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