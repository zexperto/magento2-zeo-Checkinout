<?php
                
namespace Zeo\Checkinout\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
    
        if (!$context->getVersion()) {
            // your code here" . "\n";
            return null;
        }
        
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            //code to upgrade to 1.0.1" . "\n";
            return null;
        }

        if (version_compare($context->getVersion(), '1.0.2') < 0) {
             //code to upgrade to 1.0.2" . "\n";
             return null;
        }

        $setup->endSetup();
    }
}
