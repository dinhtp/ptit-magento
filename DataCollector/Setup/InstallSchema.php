<?php
declare(strict_types=1);

namespace Master\DataCollector\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 * @package Master\DataCollector\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();

        $masterTables = $this->prepareTablesAndColumns();
        foreach ($masterTables as $tableName => $columns) {
            if ($connection->isTableExists($tableName)) {
                continue;
            }

            $table = $connection->newTable($tableName);
            foreach ($columns as $name => $column) {
                $table->addColumn($name, $column['type'], $column['size'], $column['options']);
            }
            $connection->createTable($table);
        }

        $installer->endSetup();
    }

    /**
     * @return array
     */
    public function prepareTablesAndColumns() : array
    {
        $activityLogColumns = [
            'id' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true
                ]
            ],
            'customer_id' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['nullable' => false, 'unsigned' => true]
            ],
            'total_wishlist' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['unsigned' => true, 'default' => 0]
            ],
            'total_login' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['unsigned' => true, 'default' => 0]
            ],
            'total_product_view' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['unsigned' => true, 'default' => 0]
            ],
            'total_product_search' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['unsigned' => true, 'default' => 0]
            ],
            'is_subscribed' => [
                'type' => Table::TYPE_SMALLINT,
                'size' => '2',
                'options' => ['unsigned' => true, 'default' => 0]
            ],
            'created_at' => [
                'type' => Table::TYPE_TIMESTAMP,
                'size' => null,
                'options' =>  ['nullable' => false, 'default' => Table::TIMESTAMP_INIT]
            ],
            'updated_at' => [
                'type' => Table::TYPE_TIMESTAMP,
                'size' => null,
                'options' => ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE]
            ],
        ];

        $saleLogColumns = [
            'id' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true
                ]
            ],
            'customer_id' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['nullable' => false, 'unsigned' => true]
            ],
            'total_order' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['unsigned' => true, 'default' => 0]
            ],
            'total_complete_order' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['unsigned' => true, 'default' => 0]
            ],
            'total_cancel_order' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['unsigned' => true, 'default' => 0]
            ],
            'life_time_sale' => [
                'type' => Table::TYPE_DECIMAL,
                'size' => '12,4',
                'options' => ['default' => 0]
            ],
            'average_order_total' => [
                'type' => Table::TYPE_DECIMAL,
                'size' => '12,4',
                'options' => ['default' => 0]
            ],
            'cancel_rate' => [
                'type' => Table::TYPE_DECIMAL,
                'size' => '12,4',
                'options' => ['default' => 0]
            ],
            'created_at' => [
                'type' => Table::TYPE_TIMESTAMP,
                'size' => null,
                'options' =>  ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'comment' => 'Created At'
            ],
            'updated_at' => [
                'type' => Table::TYPE_TIMESTAMP,
                'size' => null,
                'options' => ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                'comment' => 'Updated At'
            ],
        ];

        $cartLogColumns = [
            'id' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true
                ]
            ],
            'customer_id' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['nullable' => false, 'unsigned' => true]
            ],
            'total_cart' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['unsigned' => true, 'default' => 0]
            ],
            'total_complete_cart' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['unsigned' => true, 'default' => 0]
            ],
            'total_abandon_cart' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['unsigned' => true, 'default' => 0]
            ],
            'abandon_rate' => [
                'type' => Table::TYPE_DECIMAL,
                'size' => '12,4',
                'options' => ['default' => 0]
            ],
            'average_cart_total' => [
                'type' => Table::TYPE_DECIMAL,
                'size' => '12,4',
                'options' => ['default' => 0]
            ],
            'created_at' => [
                'type' => Table::TYPE_TIMESTAMP,
                'size' => null,
                'options' =>  ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            ],
            'updated_at' => [
                'type' => Table::TYPE_TIMESTAMP,
                'size' => null,
                'options' => ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
            ],
        ];

        $productLogColumns = [
            'id' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true
                ]
            ],
            'product_id' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['nullable' => false, 'unsigned' => true]
            ],
            'total_cart_presence' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['unsigned' => true, 'default' => 0]
            ],
            'total_complete' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['unsigned' => true, 'default' => 0]
            ],
            'total_abandon' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['unsigned' => true, 'default' => 0]
            ],
            'abandon_rate' => [
                'type' => Table::TYPE_DECIMAL,
                'size' => '12,4',
                'options' => ['default' => 0]
            ],
            'complete_rate' => [
                'type' => Table::TYPE_DECIMAL,
                'size' => '12,4',
                'options' => ['default' => 0]
            ],
            'created_at' => [
                'type' => Table::TYPE_TIMESTAMP,
                'size' => null,
                'options' =>  ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            ],
            'updated_at' => [
                'type' => Table::TYPE_TIMESTAMP,
                'size' => null,
                'options' => ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
            ],
        ];

        $reviewLogs = [
            'id' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true
                ]
            ],
            'customer_id' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['nullable' => false, 'unsigned' => true]
            ],
            'total_rating' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['unsigned' => true, 'default' => 0]
            ],
            'total_review' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['unsigned' => true, 'default' => 0]
            ],
            'average_rating_score' => [
                'type' => Table::TYPE_DECIMAL,
                'size' => '12,4',
                'options' => ['unsigned' => true, 'default' => 0]
            ],
            'average_review_length' => [
                'type' => Table::TYPE_DECIMAL,
                'size' => '12,4',
                'options' => ['default' => 0]
            ],
            'created_at' => [
                'type' => Table::TYPE_TIMESTAMP,
                'size' => null,
                'options' =>  ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            ],
            'updated_at' => [
                'type' => Table::TYPE_TIMESTAMP,
                'size' => null,
                'options' => ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
            ],
        ];

        $saleSessionColumns = [
            'id' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true
                ]
            ],
            'customer_id' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['nullable' => false, 'unsigned' => true]
            ],
            'cart_id' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['nullable' => false, 'unsigned' => true]
            ],
            'device_type' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['nullable' => true]
            ],
            'origin' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['nullable' => true]
            ],
            'average_interval' => [
                'type' => Table::TYPE_DECIMAL,
                'size' => '12,4',
                'options' => []
            ],
            'total_view' => [
                'type' => Table::TYPE_BIGINT,
                'size' => 11,
                'options' => ['nullable' => true]
            ],
            'total_product_view' => [
                'type' => Table::TYPE_BIGINT,
                'size' => 11,
                'options' => ['nullable' => true]
            ],
            'total_cart_view' => [
                'type' => Table::TYPE_BIGINT,
                'size' => 11,
                'options' => ['nullable' => true]
            ],
            'total_category_view' => [
                'type' => Table::TYPE_BIGINT,
                'size' => 11,
                'options' => ['nullable' => true]
            ],
            'total_search' => [
                'type' => Table::TYPE_BIGINT,
                'size' => 11,
                'options' => ['nullable' => true]
            ],
            'total_item_qty' => [
                'type' => Table::TYPE_BIGINT,
                'size' => 11,
                'options' => ['nullable' => true]
            ],
            'total_cart_value' => [
                'type' => Table::TYPE_DECIMAL,
                'size' => '12,4',
                'options' => ['nullable' => true]
            ],
            'is_checkout_cart' => [
                'type' => Table::TYPE_SMALLINT,
                'size' => 1,
                'options' => ['nullable' => true]
            ],
            'created_at' => [
                'type' => Table::TYPE_TIMESTAMP,
                'size' => null,
                'options' =>  ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            ],
            'updated_at' => [
                'type' => Table::TYPE_TIMESTAMP,
                'size' => null,
                'options' => ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
            ],
        ];

        $sessionActivityColumns = [
            'id' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true
                ]
            ],
            'sale_session_id' => [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['nullable' => false, 'unsigned' => true]
            ],
            'action_name' => [
                'type' => Table::TYPE_TEXT,
                'size' => 255,
                'options' => ['nullable' => false]
            ],
            'require_type' => [
                'type' => Table::TYPE_TEXT,
                'size' => 255,
                'options' => ['nullable' => true]
            ],
            'created_at' => [
                'type' => Table::TYPE_TIMESTAMP,
                'size' => null,
                'options' =>  ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            ],
        ];

        return [
            'master_activity_log' => $activityLogColumns,
            'master_cart_log' => $cartLogColumns,
            'master_product_log' => $productLogColumns,
            'master_review_log' => $reviewLogs,
            'master_sale_log' => $saleLogColumns,
            'master_sale_session' => $saleSessionColumns,
            'master_session_activity' => $sessionActivityColumns,
        ];
    }
}
