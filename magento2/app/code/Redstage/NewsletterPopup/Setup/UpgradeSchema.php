<?php

namespace Redstage\NewsletterPopup\Setup;

use \Magento\Framework\Setup\SchemaSetupInterface;
use \Magento\Framework\Setup\ModuleContextInterface;

/**
 * Class UpgradeSchema
 *
 * @package Redstage\NewsletterPopup\Setup
 */
class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{
    /**
     * Insert new columns into table Newsletter Subscriber
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.2.0', '<=')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('newsletter_subscriber'),
                'name',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Subscriber Name'
                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable('newsletter_subscriber'),
                'phone',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 25,
                    'nullable' => true,
                    'comment' => 'Subscriber Phone'
                ]
            );
        }

        $setup->endSetup();
    }
}
