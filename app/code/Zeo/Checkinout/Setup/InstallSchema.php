<?php

namespace Zeo\Checkinout\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $setup->startSetup();
        
        
        $table = $setup->getConnection()
            ->newTable($setup->getTable('zeo_checkinout_day'))
            ->addColumn( 'entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
                ],
                'Id'
            )
            ->addColumn('customer_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['nullable' => false ], 'Customer ID')
            ->addColumn('day', \Magento\Framework\DB\Ddl\Table::TYPE_DATE, null, [], 'Day')
            ->addColumn('note', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '255', ['nullable' => false ], 'Note' )
            ->addColumn('day_sequence', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 1, ['default' => 0], 'Day Sequence, 1-7 Monday(1)')
            ->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 1, ['default' => null], 'Status');
        $setup->getConnection()->createTable($table);
                        
        $table = $setup->getConnection()
            ->newTable($setup->getTable('zeo_checkinout_hour'))
            ->addColumn( 'entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
                ],
                'Id'
            )
            ->addColumn('day_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['nullable' => false, 'unsigned' => true], 'Day')
            ->addColumn('start_time', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '8', ['nullable' => false ], 'Start Time' )
            ->addColumn('end_time', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '8', ['nullable' => false ], 'End Time' )
            ->addColumn('total_time', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '8', ['nullable' => false ], 'Total Time' )
            ->addColumn('total', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4', ['nullable' => false, 'default' => '0.0000'], 'Total' )
            ->addColumn('type', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 1, ['default' => null], 'Type')
            ->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 1, ['default' => null], 'Status')
            ->addForeignKey(
                $setup->getFkName('zeo_hour', 'day_id', 'zeo_day', 'entity_id'),
                'day_id',
                $setup->getTable('zeo_checkinout_day'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE);
            
                
        $setup->getConnection()->createTable($table);
                         
        
        $setup->endSetup();
    }
}
