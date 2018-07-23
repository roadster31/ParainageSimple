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
use ParainageSimple\ParainageSimpleHelper;
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

            /*
             * TODO : check if we need to query coupon_customer_count table
             * to ensure the coupon is associated to the customer instead
             * just checking coupon code
            */
            // Creer un coupon du montant de la remise, et le placer dans la commande.
            $sponsorCouponCode = ParainageSimpleHelper::getSponsorCouponCode($sponsorship->getBeneficiaryId(), $sponsorship->getSponsorId());
            $beneficiaryCouponCode = ParainageSimpleHelper::getBeneficiaryCouponCode($sponsorship->getBeneficiaryId(), $sponsorship->getSponsorId());
            /** @noinspection PhpParamsInspection */
            $sponsorCoupon = CouponQuery::create()->findOneByCode($sponsorCouponCode);
            $beneficiaryCoupon = CouponQuery::create()->findOneByCode($beneficiaryCouponCode);

            /** @noinspection PhpParamsInspection */
            $loopResultRow = (new LoopResultRow())
                ->set('ID', $sponsorship->getId())
                ->set('SPONSOR_ID', $sponsorship->getSponsorId())
                ->set('BENEFICIARY_ID', $sponsorship->getBeneficiaryId())
                ->set('BENEFICIARY_EMAIL', $sponsorship->getBeneficiaryEmail())
                ->set('BENEFICIARY_FIRSTNAME', $sponsorship->getBeneficiaryFirstname())
                ->set('BENEFICIARY_LASTNAME', $sponsorship->getBeneficiaryLastname())
                ->set('SPONSOR_COUPON_AMOUNT',  money_format("%n", empty($sponsorCoupon) ? 0 : $sponsorCoupon->getAmount()))
                ->set('BENEFICIARY_COUPON_AMOUNT',  money_format("%n", empty($beneficiaryCoupon) ? 0 : $beneficiaryCoupon->getAmount()))
                ->set('STATUS', SponsorshipStatusI18nQuery::create()->findOneById( $sponsorship->getStatus())->getTitle()
                );

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}