<?php
/**
 * Created by PhpStorm.
 * User: audreymartel
 * Date: 18/07/2018
 * Time: 14:33
 */

namespace ParainageSimple;


use ParainageSimple\Model\SponsorshipDiscountType;
use ParainageSimple\Model\SponsorshipStatus;
use ParainageSimple\Model\SponsorshipStatusQuery;
use Propel\Runtime\Exception\PropelException;
use Thelia\Log\Tlog;
use Thelia\Model\Message;
use Thelia\Model\MessageQuery;

class ParainageSimpleConfiguration
{
    const CONFIG_KEY_USE_INVITATION_CODE = 'use_invitation_code';
    const CONFIG_KEY_SPONSORSHIP_TYPE = 'type';
    const CONFIG_KEY_SPONSOR_DISCOUNT_AMOUNT = 'valeur_parrain';
    const CONFIG_KEY_MINIMUM_CART_AMOUNT = 'minimum_achat';
    const CONFIG_KEY_BENEFICIARY_DISCOUNT_AMOUNT = 'valeur_filleul';
    const MESSAGE_NAME_MAIL_SPONSOR = 'parrainage_simple_mail_parrain';
    const MESSAGE_NAME_MAIL_INVITATION_BENEFICIARY = 'parrainage_simple_mail_filleul';
    const MESSAGE_NAME_MAIL_CODE_INVITATION_BENEFICIARY = 'parrainage_simple_mail_code_filleul';
    const TYPE_DISCOUNT_BENEFICIARY = SponsorshipDiscountType::TYPE_PERCENT;

    /** @var ParainageSimpleConfiguration  */
    private static $_instance = null;

    public static function getInstance() {

        if(is_null(self::$_instance)) {
            self::$_instance = new ParainageSimpleConfiguration();
        }

        return self::$_instance;
    }

    public static function getMessageCodeForInvitation()
    {
        if (self::useInvitationCode()) {
            return self::MESSAGE_NAME_MAIL_CODE_INVITATION_BENEFICIARY;
        }
        return self::MESSAGE_NAME_MAIL_INVITATION_BENEFICIARY;
    }

    /**
     * @param $force
     */
    public function registerDefaultValuesInDatabase($force = false)
    {
        $this->registerDefaultConfigurationValues();
        $this->registerDefaultEmails($force);
        $this->registerDefaultDiscountStatus();
    }

    private function registerDefaultConfigurationValues()
    {
        if (self::getSponsorshipType() === null) {
            ParainageSimple::setConfigValue(self::CONFIG_KEY_SPONSORSHIP_TYPE, SponsorshipDiscountType::TYPE_PERCENT);
        }
        if (self::getSponsorDiscountAmount() === null) {
            ParainageSimple::setConfigValue(self::CONFIG_KEY_SPONSOR_DISCOUNT_AMOUNT, 10);
        }
        if (self::getBeneficiaryDiscountAmount() === null) {
            ParainageSimple::setConfigValue(self::CONFIG_KEY_BENEFICIARY_DISCOUNT_AMOUNT, 5);
        }
        if (self::getMinimumCartAmount() === null) {
            ParainageSimple::setConfigValue(self::CONFIG_KEY_MINIMUM_CART_AMOUNT, 20);
        }
    }

    private function registerDefaultDiscountStatus()
    {
        try {
            if (null !== SponsorshipStatusQuery::create()->findOne()) {
                return;
            }

            $status = new SponsorshipStatus();
            $status->setLocale('fr_FR');
            $status->setCode(SponsorshipStatus::CODE_INVITATION_SENT);
            $status->setTitle('INVITATION ENVOYÉE');
            $status->setColor('#ff0000');
            $status->setPosition(0);
            $status->save();

            //we may add status for invitation received in the future

            $status = new SponsorshipStatus();
            $status->setLocale('fr_FR');
            $status->setCode(SponsorshipStatus::CODE_INVITATION_ACCEPTED);
            $status->setTitle('INVITATION ACCEPTÉE');
            $status->setColor('#ff7d00');
            $status->setPosition(1);
            $status->save();
        } catch (PropelException $e) {
            Tlog::getInstance()->error($e->getMessage());
        }
    }

    /**
     * @param bool $force
     */
    private function registerDefaultEmails($force = false)
    {
        /** @noinspection PhpParamsInspection */
        if ($force || null === MessageQuery::create()->findOneByName(self::MESSAGE_NAME_MAIL_SPONSOR)) {
            $message = new Message();

            try {
                $message
                    ->setName(self::MESSAGE_NAME_MAIL_SPONSOR)
                    ->setHtmlTemplateFileName('mail-parrain.html')
                    ->setTextTemplateFileName('mail-parrain.txt')
                    ->setHtmlLayoutFileName('')
                    ->setTextLayoutFileName('')
                    ->setSecured(0)
                    ->setLocale('fr_FR')
                    ->setTitle('Mail d\'envoi du code promo au parrain suite à parrainage')
                    ->setSubject('Recevez {$label_promo} sur votre prochaine commande !')
                    ->save();
            } catch (PropelException $e) {
                Tlog::getInstance()->error($e->getMessage());
            }
        }


        /** @noinspection PhpParamsInspection */
        if ($force || null === MessageQuery::create()->findOneByName(self::MESSAGE_NAME_MAIL_CODE_INVITATION_BENEFICIARY)) {
            $message = new Message();

            try {
                $message
                    ->setName(self::MESSAGE_NAME_MAIL_CODE_INVITATION_BENEFICIARY)
                    ->setHtmlTemplateFileName('mail-filleul-with-code.html')
                    ->setTextTemplateFileName('mail-filleul-with-code.txt')
                    ->setHtmlLayoutFileName('')
                    ->setTextLayoutFileName('')
                    ->setSecured(0)
                    ->setLocale('fr_FR')
                    ->setTitle('Mail d\'invitation au filleul suite à parrainage')
                    ->setSubject('{loop type="customer" name="parrain" current="false" id=$id_parrain}{$FIRSTNAME} {$LASTNAME}{/loop} souhaite vous parrainer sur {config key="store_name"} !')
                    ->save();
            } catch (PropelException $e) {
                Tlog::getInstance()->error($e->getMessage());
            }
        }

        /** @noinspection PhpParamsInspection */
        if ($force || null === MessageQuery::create()->findOneByName(self::MESSAGE_NAME_MAIL_INVITATION_BENEFICIARY)) {
            $message = new Message();

            try {
                $message
                    ->setName(self::MESSAGE_NAME_MAIL_INVITATION_BENEFICIARY)
                    ->setHtmlTemplateFileName('mail-filleul.html')
                    ->setTextTemplateFileName('mail-filleul.txt')
                    ->setHtmlLayoutFileName('')
                    ->setTextLayoutFileName('')
                    ->setSecured(0)
                    ->setLocale('fr_FR')
                    ->setTitle('Mail d\'invitation au filleul suite à parrainage')
                    ->setSubject('{loop type="customer" name="parrain" current="false" id=$id_parrain}{$FIRSTNAME} {$LASTNAME}{/loop} souhaite vous parrainer sur {config key="store_name"} !')
                    ->save();
            } catch (PropelException $e) {
                Tlog::getInstance()->error($e->getMessage());
            }
        }
    }

    public static function setUseInvitationCode($use) {
        ParainageSimple::setConfigValue(self::CONFIG_KEY_USE_INVITATION_CODE, $use);
    }

    /**
     * @return bool
     */
    public static function useInvitationCode() {
        return ParainageSimple::getConfigValue(self::CONFIG_KEY_USE_INVITATION_CODE);
    }

    public static function getMinimumCartAmount() {
        return ParainageSimple::getConfigValue(self::CONFIG_KEY_MINIMUM_CART_AMOUNT);
    }

    public static function getSponsorDiscountAmount() {
        return ParainageSimple::getConfigValue(self::CONFIG_KEY_SPONSOR_DISCOUNT_AMOUNT);
    }

    public static function getBeneficiaryDiscountAmount() {
        return ParainageSimple::getConfigValue(self::CONFIG_KEY_BENEFICIARY_DISCOUNT_AMOUNT);
    }

    public static function getSponsorshipType() {
        return ParainageSimple::getConfigValue(self::CONFIG_KEY_SPONSORSHIP_TYPE);
    }

}