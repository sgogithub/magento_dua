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
            $connection->query("INSERT INTO `sales_order_status` (`status`, `label`) VALUES ('accpt_espay_bcaatm', 'ESPay BCA VA Online');");

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
