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

namespace ParainageSimple;

use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Model\Currency;
use Thelia\Model\Message;
use Thelia\Model\MessageQuery;
use Thelia\Module\BaseModule;

class ParainageSimple extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'parainagesimple';
    
    const TYPE_PARAINAGE = 'type';
    const VALEUR_REMISE_PARRAIN = 'valeur_parrain';
    const VALEUR_REMISE_FILLEUL = 'valeur_filleul';
    const MONTANT_ACHAT_MINIMUM  = 'minimum_achat';
    
    const TYPE_POURCENTAGE = 'p';
    const TYPE_SOMME = 's';
    
    const MAIL_PARRAIN = 'parrainage_simple_mail_parrain';
    const MAIL_INVITATION_FILLEUL = 'parrainage_simple_mail_filleul';
    
    public static function getlabelPromo($type, $valeur, $mini)
    {
        $labelCurrency = Currency::getDefaultCurrency()->getSymbol();
        
        $labelPromo = $valeur . ($type == ParainageSimple::TYPE_POURCENTAGE ? '%' : ' ' . $labelCurrency);
        
        if ($mini > 0) {
            $labelPromo .= " à partir de ".sprintf("%1.2f", $mini)." $labelCurrency d'achat";
        }
        
        return $labelPromo;
    }
    
    
    public function postActivation(ConnectionInterface $con = null)
    {
        self::setConfigValue(self::TYPE_PARAINAGE, self::TYPE_POURCENTAGE);
        self::setConfigValue(self::VALEUR_REMISE_PARRAIN, 10);
        self::setConfigValue(self::VALEUR_REMISE_FILLEUL, 5);
        self::setConfigValue(self::MONTANT_ACHAT_MINIMUM, 20);
    
        if (null === MessageQuery::create()->findOneByName(self::MAIL_PARRAIN)) {
            $message = new Message();
        
            $message
                ->setName(self::MAIL_PARRAIN)
                ->setHtmlTemplateFileName('mail-parrain.html')
                ->setTextTemplateFileName('mail-parrain.txt')
                ->setHtmlLayoutFileName('')
                ->setTextLayoutFileName('')
                ->setSecured(0)
                
                ->setLocale('fr_FR')
                ->setTitle('Mail d\'envoi du code promo au parrain suite à parrainage')
                ->setSubject('Recevez {$label_promo} sur votre prochaine commande !')
                ->save();
        }
    
    
        if (null === MessageQuery::create()->findOneByName(self::MAIL_INVITATION_FILLEUL)) {
            $message = new Message();
        
            $message
                ->setName(self::MAIL_INVITATION_FILLEUL)
                ->setHtmlTemplateFileName('mail-filleul.html')
                ->setTextTemplateFileName('mail-filleul.txt')
                ->setHtmlLayoutFileName('')
                ->setTextLayoutFileName('')
                ->setSecured(0)
            
                ->setLocale('fr_FR')
                ->setTitle('Mail d\'invitation au filleul suite à parrainage')
                ->setSubject('{loop type="customer" name="parrain" current="false" id=$id_parrain}{$FIRSTNAME} {$LASTNAME}{/loop} souhaite vous parrainer sur {config key="store_name"} !')
                ->save();
        }
    }
}
