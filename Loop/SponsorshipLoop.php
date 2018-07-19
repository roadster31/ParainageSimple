<?php
/**
 * Created by PhpStorm.
 * User: audreymartel
 * Date: 18/07/2018
 * Time: 11:10
 */

namespace ParainageSimple\Loop;


use ParainageSimple\Model\Map\SponsorshipStatusTableMap;
use ParainageSimple\Model\Map\SponsorshipTableMap;
use ParainageSimple\Model\Sponsorship;
use ParainageSimple\Model\SponsorshipQuery;
use ParainageSimple\Model\SponsorshipStatusI18nQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Model\CouponQuery;

class SponsorshipLoop extends BaseLoop implements PropelSearchLoopInterface
{
    /**
     * Definition of loop arguments
     * @return \Thelia\Core\Template\Loop\Argument\ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument('id'),
            Argument::createIntListTypeArgument('sponsor_id'),
            Argument::createAlphaNumStringListTypeArgument('beneficiary_email'),
            Argument::createAlphaNumStringTypeArgument('status')
        );
    }

    public function buildModelCriteria()
    {
        $sponsorshipQuery = SponsorshipQuery::create();

        /** @noinspection PhpUndefinedMethodInspection */
        if (null !== $id = $this->getId()) {
            $sponsorshipQuery->filterById($id, Criteria::IN);
        }

        /** @noinspection PhpUndefinedMethodInspection */
        if (null !== $sponsorId = $this->getSponsorId()) {
            $sponsorshipQuery->filterBySponsorId($sponsorId, Criteria::IN);
        }

        /** @noinspection PhpUndefinedMethodInspection */
        if (null !== $beneficiaryEmail = $this->getBeneficiaryEmail()) {
            $sponsorshipQuery->filterByBeneficiaryEmail($beneficiaryEmail, Criteria::IN);
        }

        /** @noinspection PhpUndefinedMethodInspection */
        if (null !== $status = $this->getStatus()) {
            $sponsorshipQuery->filterByStatus($status, Criteria::EQUAL);
        }
        $sponsorshipQuery->innerJoinSponsorshipStatus(SponsorshipStatusTableMap::TABLE_NAME);
        $sponsorshipQuery->where(SponsorshipTableMap::STATUS . Criteria::EQUAL . SponsorshipStatusTableMap::ID);

        return $sponsorshipQuery;
    }

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        /** @var Sponsorship $sponsorship */
        foreach ($loopResult->getResultDataCollection() as $sponsorship) {

            // Creer un coupon du montant de la remise, et le placer dans la commande.
            $code = sprintf('PARRAINAGE%dP%d', $sponsorship->getBeneficiaryId(), $sponsorship->getSponsorId());
            /** @noinspection PhpParamsInspection */
            $coupon = CouponQuery::create()->findOneByCode($code);
            $coupon->getAmount();

            /** @noinspection PhpParamsInspection */
            $loopResultRow = (new LoopResultRow())
                ->set('ID', $sponsorship->getId())
                ->set('SPONSOR_ID', $sponsorship->getSponsorId())
                ->set('BENEFICIARY_ID', $sponsorship->getBeneficiaryId())
                ->set('BENEFICIARY_EMAIL', $sponsorship->getBeneficiaryEmail())
                ->set('BENEFICIARY_FIRSTNAME', $sponsorship->getBeneficiaryFirstname())
                ->set('BENEFICIARY_LASTNAME', $sponsorship->getBeneficiaryLastname())
                ->set('SPONSOR_COUPON_AMOUNT',  money_format("%n", empty($coupon) ? 0 : $coupon->getAmount()))
                ->set('STATUS', SponsorshipStatusI18nQuery::create()->findOneById( $sponsorship->getStatus())->getTitle()
                );

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}