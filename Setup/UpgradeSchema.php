<?php
namespace RedInGo\CategoryModification\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
	public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
		$installer = $setup;

		$installer->startSetup();

		if(version_compare($context->getVersion(), '1.1.0','<')) {
			$installer->getConnection()->addColumn(
				$installer->getTable( 'catalog_category_entity' ),
    				'category_modification_enabled',
    				[
    					'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
    					'nullable' => true,
    					'length' => '11',
    					'comment' => 'category_modification_enabled',
    					'after' => 'children_count'
    				]
			);
            $installer->getConnection()->addColumn(
				$installer->getTable( 'catalog_category_entity' ),
                'category_modification_height',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'length' => '255',
                    'comment' => 'category_modification_height',
                    'after' => 'category_modification_enabled'
                ]
            );
            $installer->getConnection()->addColumn(
				$installer->getTable( 'catalog_category_entity' ),
                'category_modification_show_text',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'length' => '255',
                    'comment' => 'category_modification_show_text',
                    'after' => 'category_modification_height'
                ]
            );
            $installer->getConnection()->addColumn(
				$installer->getTable( 'catalog_category_entity' ),
                'category_modification_hide_text',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'length' => '255',
                    'comment' => 'category_modification_hide_text',
                    'after' => 'category_modification_show_text'
                ]
            );
            $installer->getConnection()->addColumn(
				$installer->getTable( 'catalog_category_entity' ),
                'category_name_alternative',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'length' => '255',
                    'comment' => 'category_name_alternative',
                    'after' => 'category_modification_hide_text'
                ]
            );
		}



		$installer->endSetup();
	}
}
