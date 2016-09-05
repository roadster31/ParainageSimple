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

use Propel\Runtime\Connection\PdoConnection;
use Propel\Runtime\Propel;
use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Model\Customer;
use Thelia\Model\Map\CouponTableMap;
use Thelia\Model\Map\CustomerTableMap;

class ParainageLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection();
    }

    public function buildArray()
    {
        /** @var PdoConnection $con */
        $con = Propel::getConnection();
    
        $sql = "
            SELECT ".CustomerTableMap::ID." as CustomerId, ".CouponTableMap::ID." as CouponId
            FROM ".CustomerTableMap::TABLE_NAME."
            LEFT JOIN ".CouponTableMap::TABLE_NAME."
            ON ".CouponTableMap::CODE." = CONCAT('PAR', ".CustomerTableMap::ID.", 'P', ".CustomerTableMap::SPONSOR.")
            WHERE ".CustomerTableMap::SPONSOR." IS NOT NULL
            AND ".CouponTableMap::ID." IS NOT NULL
            ORDER BY ".CustomerTableMap::REF
        ;

        $stmt = $con->prepare($sql);
        
        $result = [];
    
        if ($stmt->execute()) {
            while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $result[] = $data;
            }
        }
        return $result;
    }

    public function parseResults(LoopResult $loopResult)
    {
        /** @var Customer $customer */
        foreach ($loopResult->getResultDataCollection() as $data) {
            $loopResultRow = (new LoopResultRow())
                ->set('CUSTOMER_ID', $data['CustomerId'])
                ->set('COUPON_ID', $data['CouponId']);

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}
