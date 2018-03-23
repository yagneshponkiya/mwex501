<?php
namespace Vendor\Pricematrix\Setup;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {

        // $context->getVersion() = version du module actuelle
        // 10.0.0 = version en cours d'installation
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $installer = $setup;
            $installer->startSetup();
			
			 $installer->getConnection()->addColumn(
                $installer->getTable('custom_pricematrix'),
                'custom_price',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 120,
                    'nullable' => true,
                    'comment' => 'Custom Price'
                ]
            );
			
			
			
            // do what you have to do

            $installer->endSetup();
        }
		
		 if (version_compare($context->getVersion(), '1.0.2') < 0) {
            $installer = $setup;
            $installer->startSetup();
			
			
			$installer->getConnection()->addColumn(
                $installer->getTable('custom_pricematrix'),
                'last_sync',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                   null,
                     ['nullable' => false],
                    'comment' => 'Last Sync'
                ]
            );
			
			
			$installer->getConnection()->addColumn(
                $installer->getTable('custom_pricematrix'),
                'creation_time',
                [
                    'type' =>  \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                   null,
                     ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                    'comment' => 'creation time'
                ]
            );
			
			$installer->getConnection()->addColumn(
                $installer->getTable('custom_pricematrix'),
                'update_time',
                [
                    'type' =>  \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                   null,
                     ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                    'comment' => 'update time'
                ]
            );
			
			
			
            // do what you have to do

            $installer->endSetup();
        }
		
		
		
    }
}
