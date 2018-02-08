<?php

namespace Espay\Pg\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $connection = $installer->getConnection();
        try {
            $connection->beginTransaction();

            // Do a bunch of stuff here that may change things in the database and
            // you want to retain the option to rollback the changes if an error occurs

            // table order status
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_bcaatm', 'ESPay BCA VA Online');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_bcaklikpay', 'ESPay BCA KlikPay');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_xltunai', 'ESPay XL TUNAI');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_biiatm', 'ESPay ATM MULTIBANK');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_bnidbo', 'ESPay BNI Debit Online');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_epaybri', 'ESPay e-Pay BRI');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_briatm', 'ESPay BRI ATM');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_danamonob', 'ESPay Danamon Online Banking');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_danamonatm', 'ESPay ATM Danamon');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_dkiib', 'ESPay DKI IB');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_mandirisms', 'ESPay MANDIRI SMS');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_finpay195', 'ESPay Modern Channel');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_mandiriecash', 'ESPay MANDIRI E-CASH');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_creditcard', 'ESPay Credit Card Visa / Master');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_mandiriib', 'ESPay MANDIRI IB');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_maspionatm', 'ESPay ATM MASPION');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_mayapadaib', 'ESPay Mayapada Internet Banking');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_muamalatatm', 'ESPay MUAMALAT ATM');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_nobupay', 'ESPay Nobu Pay');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_permataatm', 'ESPay PERMATA ATM');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_permatapeb', 'ESPay Permata ebusiness');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_permatanetpay', 'ESPay PermataNet');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_emoedikk2', 'ESPay EMOEDIKK2');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_emoedikk', 'ESPay EMOEDIKK');");
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('payment_accepted_espay', 'ESPay Payment');");

            // table order status state
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_bcaatm', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_bcaklikpay', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_xltunai', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_biiatm', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_bnidbo', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_epaybri', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_briatm', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_danamonob', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_danamonatm', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_dkiib', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_mandirisms', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_finpay195', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_mandiriecash', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_creditcard', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_mandiriib', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_maspionatm', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_mayapadaib', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_muamalatatm', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_nobupay', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_permataatm', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_permatapeb', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_permatanetpay', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_emoedikk2', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('accpt_espay_emoedikk', 'processing',0,1);");
            $connection->query("INSERT INTO `sales_order_status_state` (`status`, `state`, `is_default`, `visible_on_front`) VALUES ('payment_accepted_espay', 'processing',0,1);");

            // If no errors occur commit all the database changes
            $connection->commit();
        } catch (\Exception $e) {

            // If an error occured rollback the database changes as if they never happened
            $connection->rollback();
            throw $e;
        }

        $installer->endSetup();
    }
}
