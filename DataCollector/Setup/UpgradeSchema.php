<?php
declare(strict_types=1);

namespace Master\DataCollector\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Class UpgradeSchema
 * @package Master\DataCollector\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();

        if (!$connection->isTableExists('master_sale_history')
            && version_compare($context->getVersion(), '0.0.2', '<=')
        ) {
            $sql = 'CREATE VIEW master_sale_history AS SELECT ';
            $sql .= 'sl.customer_id, sl.total_order, sl.total_complete_order, sl.life_time_sale, sl.average_order_total,';
            $sql .= 'cl.total_cart, cl.total_complete_cart, cl.total_abandon_cart, cl.average_cart_total,';
            $sql .= 'rl.total_rating, rl.total_review, rl.average_rating_score, rl.average_review_length,';
            $sql .= 'al.total_wishlist, al.total_login, al.total_product_view, al.total_product_search,';
            $sql .= 'cl.abandon_rate ';
            $sql .= 'FROM master_sale_log AS sl ';
            $sql .= 'INNER JOIN master_cart_log AS cl ';
            $sql .= 'ON sl.customer_id = cl.customer_id ';
            $sql .= 'INNER JOIN master_review_log as rl ';
            $sql .= 'ON sl.customer_id = rl.customer_id ';
            $sql .= 'INNER JOIN  master_activity_log as al ';
            $sql .= 'ON sl.customer_id = al.customer_id';

            $connection->query($sql);
        }

        $installer->endSetup();
    }
}
