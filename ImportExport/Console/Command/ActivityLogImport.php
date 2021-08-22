<?php
declare(strict_types=1);

namespace Master\ImportExport\Console\Command;

use Exception;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ActivityLogImport
 * @package Master\ImportExport\Console\Command
 */
class ActivityLogImport extends AbstractImport
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('master:activity:import');
        $this->setDescription('Import Activity Log Data');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output) :int
    {
        $table = $this->resource->getTableName('master_activity_log');
        $connection = $this->resource->getConnection();

        $connection->truncateTable($table);
        $totalCustomer = $this->getTotalCustomer();
        for ($i = 1; $i <= $totalCustomer; $i++) {
            $connection->insertMultiple($table, $this->prepareData($i, $this->getCustomerType($i)));
        }

        return Cli::RETURN_SUCCESS;
    }

    /**
     * @param int $id
     * @param string $type
     * @return array
     * @throws Exception
     */
    protected function prepareData($id, $type) :array
    {
        $totalWishlist = random_int(0, 5);
        if ($type === self::TYPE_ACTIVE) {
            $totalWishlist = random_int(20, 50);
        }
        if ($type === self::TYPE_REGULAR) {
            $totalWishlist = random_int(20, 50);
        }

        $totalView = random_int(2000, 3000);
        if ($type === self::TYPE_ACTIVE) {
            $totalView = random_int(800, 2000);
        }
        if ($type === self::TYPE_REGULAR) {
            $totalView = random_int(500, 1500);
        }

        $totalSearch = random_int(1500, 2500);
        if ($type === self::TYPE_ACTIVE) {
            $totalSearch = random_int(400, 800);
        }
        if ($type === self::TYPE_REGULAR) {
            $totalSearch = random_int(500, 1200);
        }

        return [
            'customer_id' => $id,
            'total_wishlist' => $totalWishlist,
            'total_login' => random_int(10, 60),
            'total_product_view' => $totalView,
            'total_product_search' => $totalSearch,
            'is_subscribed' => random_int(0, 1),
        ];
    }
}
