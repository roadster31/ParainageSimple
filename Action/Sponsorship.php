<?php
/**
 * Created by PhpStorm.
 * User: audreymartel
 * Date: 16/07/2018
 * Time: 14:00
 */

namespace ParainageSimple\Action;


use ParainageSimple\Event\SponsorshipCreateEvent;
use ParainageSimple\Event\SponsorshipDeleteEvent;
use ParainageSimple\Event\SponsorshipUpdateEvent;
use ParainageSimple\Model\Sponsorship as SponsorshipModel;
use ParainageSimple\Model\SponsorshipQuery;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Action\BaseAction;
use Thelia\Core\Translation\Translator;

class Sponsorship extends BaseAction implements EventSubscriberInterface
{
    const SPONSORSHIP_CREATE = 'parainage_simple.action.sponsorship.create';
    const SPONSORSHIP_UPDATE = 'parainage_simple.action.sponsorship.update';
    const SPONSORSHIP_DELETE = 'parainage_simple.action.sponsorship.delete';

    /**
     * @param SponsorshipCreateEvent $event
     * @throws \Exception
     */
    public function create(SponsorshipCreateEvent $event)
    {
        $existingSponsorship =
            SponsorshipQuery::create()
                ->filterBySponsorId($event->getSponsorId())
                ->filterByBeneficiaryEmail($event->getEmail())
                ->findOne();
        if ($existingSponsorship !== null) {
            throw new \Exception(Translator::getInstance()->trans("You have already send an invitation to this email"));
        }
        $sponsorship = new SponsorshipModel();
        $sponsorship
            ->setSponsorId($event->getSponsorId())
            ->setCode($event->getCode())
            ->setBeneficiaryEmail($event->getEmail())
            ->setBeneficiaryFirstname($event->getFirstname())
            ->setBeneficiaryLastname($event->getLastname())
            ->setStatus($event->getStatus()->getId())
            ->setSponsorId($event->getSponsorId());
        $sponsorship->save();
    }

    /**
     * @param SponsorshipUpdateEvent $event
     * @throws \Exception
     */
    public function update(SponsorshipUpdateEvent $event)
    {
        $existingSponsorship =
            SponsorshipQuery::create()
                ->filterByCode($event->getCode())
                ->findOne();
        if ($existingSponsorship === null) {
            throw new \Exception(Translator::getInstance()->trans("No invitation found"));
        }
        $existingSponsorship->setBeneficiaryId($event->getBeneficiaryId());
        $existingSponsorship->setStatus($event->getStatus()->getId());
        $existingSponsorship->save();
    }


    /**
     * @param SponsorshipDeleteEvent $event
     * @throws \Propel\Runtime\Exception\PropelException
     * @return bool true if deleted
     */
    public function delete(SponsorshipDeleteEvent $event)
    {
        $sponsorship = $query = SponsorshipQuery::create()
            ->filterByCode($event->getCode())
            ->findOne();
        if ($sponsorship === null) {
            return false;
        }
        $sponsorship->delete();
        return true;
    }


    /**
     * Returns an array of event names this subscriber wants to listen to.
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            self::SPONSORSHIP_CREATE => ["create", 128],
            self::SPONSORSHIP_UPDATE => ["update", 128],
            self::SPONSORSHIP_DELETE => ["delete", 128],
        );
    }
}