<?php

namespace Zeo\Checkinout\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class Uninstall implements UninstallInterface
{

    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        // php bin/magento module:uninstall Zeo_Checkinout
        $setup->startSetup();
        // your code here"
        $setup->endSetup();
    }
}
