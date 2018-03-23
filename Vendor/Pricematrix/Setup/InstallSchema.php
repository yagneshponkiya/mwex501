<?php
namespace Vendor\Pricematrix\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * @version 1.0.2
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        unset($context);

        $installer = $setup;
        $installer->startSetup();

        $installer->getConnection()->dropTable($installer->getTable('custom_pricematrix'));

        $customPricematrix = $installer->getConnection()
                ->newTable($installer->getTable('custom_pricematrix'))
                ->addColumn(
                    'pricematrix_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [ 'identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Matrix ID'
                )
                ->addColumn(
                    'product_style',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    120,
                    [ 'nullable' => true, 'default' => ''],
                    'Product Style'
                )
                ->addColumn(
                    'product_color',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    120,
                    ['unsigned' => true, 'nullable' => false, 'default' => ''],
                    'Product Color'
                )
                ->addColumn(
                    'product_squaremeter',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    120,
                    [ 'nullable' => true, 'default' => ''],
                    'Product Squaremeter'
                )
                ->addColumn(
                    'product_category',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    120,
                    ['nullable' => true, 'default' => ''],
                    'Product Category'
                )
                ->addIndex(
                    $installer->getIdxName('custom_pricematrix', ['pricematrix_id']),
                    ['pricematrix_id']
                )
                ->setComment('Customer PriceMatrix');

        $installer->getConnection()->createTable($customPricematrix);

        $installer->endSetup();
    }
}
