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

namespace ParainageSimple\Hook;

use ParainageSimple\ParainageSimple;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Model\CustomerQuery;
use Thelia\Model\ModuleConfig;
use Thelia\Model\ModuleConfigQuery;

class HookManager extends BaseHook
{
    public function onModuleConfigure(HookRenderEvent $event)
    {
        $vars = [];
        
        if (null !== $params = ModuleConfigQuery::create()->findByModuleId(ParainageSimple::getModuleId())) {
            /** @var ModuleConfig $param */
            foreach ($params as $param) {
                $vars[ $param->getName() ] = $param->getValue();
            }
        }

        $event->add(
            $this->render('parainage-simple/module-configuration.html', $vars)
        );
    }
    
    public function onCustomerEdit(HookRenderEvent $event)
    {
        if (null !== $customer = CustomerQuery::create()->findPk($event->getArgument('customer_id'))) {
            $event->add(
                $this->render(
                    'parainage-simple/customer-edit.html',
                    ['id_parrain' => $customer->getSponsor() ?: '0' ]
                )
            );
        }
    }
    
    public function onRegisterFormBottom(HookRenderEvent $event)
    {
        $event->add(
            $this->render('parainage-simple/register.html')
        );
    }
    
    public function afficherInvitation(HookRenderEvent $event)
    {
        $event->add(
            $this->render('parainage-simple/invitation.html')
        );
    }
}
