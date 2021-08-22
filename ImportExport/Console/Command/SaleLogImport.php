<?php
declare(strict_types=1);

namespace Master\ImportExport\Console\Command;

use Exception;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SaleLogImport
 * @package Master\ImportExport\Console\Command
 */
class SaleLogImport extends AbstractImport
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('master:sale:import');
        $this->setDescription('Import Sale Log Data');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output) :int
    {
        $connection = $this->resource->getConnection();

        $cartTable = $this->resource->getTableName('master_cart_log');
        $saleTable = $this->resource->getTableName('master_sale_log');
        $connection->truncateTable($cartTable);
        $connection->truncateTable($saleTable);


        $totalCustomer = $this->getTotalCustomer();
        for ($i = 1; $i <= $totalCustomer; $i++) {
            $cartData = $this->prepareCartData($i);
            $connection->insertMultiple($cartTable, $cartData);

            $saleData = $this->prepareSaleData($cartData);
            $connection->insertMultiple($saleTable, $saleData);
        }

        return Cli::RETURN_SUCCESS;
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    protected function prepareSaleData($data) :array
    {
        $totalOrder = $data['total_complete_cart'];
        $type = $this->getCustomerType($data['customer_id']);

        $cancelOrder = random_int(1, $totalOrder);
        if ($type === self::TYPE_ACTIVE) {
            $cancelOrder = random_int(0, 4);
        }
        if ($type === self::TYPE_REGULAR) {
            $cancelOrder = random_int(3, 6);
        }

        $cancelRate = $cancelOrder / $totalOrder;
        $completeOrder = $totalOrder - $cancelOrder;
        $orderTotal = $data['average_cart_total'] - ($data['average_cart_total'] / 100 * $cancelRate);

        return [
            'customer_id' => $data['customer_id'],
            'total_order' => $totalOrder,
            'total_complete_order' => $totalOrder - $cancelOrder,
            'total_cancel_order' => $cancelOrder,
            'life_time_sale' => $orderTotal * $completeOrder,
            'average_order_total' => $orderTotal,
            'cancel_rate' => $cancelRate,
        ];
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    protected function prepareCartData($id) :array
    {
        $type = $this->getCustomerType($id);

        $abandon = random_int(80, 180);
        if ($type === self::TYPE_ACTIVE) {
            $abandon = random_int(0, 10);
        }
        if ($type === self::TYPE_REGULAR) {
            $abandon = random_int(20, 50);
        }

        $complete = random_int(1, 20);
        if ($type === self::TYPE_ACTIVE) {
            $complete = random_int(80, 200);
        }
        if ($type === self::TYPE_REGULAR) {
            $complete = random_int(30, 50);
        }

        $average = random_int(2000, 5000);
        if ($type === self::TYPE_ACTIVE) {
            $average = random_int(90000, 160000);
        }
        if ($type === self::TYPE_REGULAR) {
            $average = random_int(10000, 50000);
        }
        return [
            'customer_id' => $id,
            'total_cart' => $complete + $abandon,
            'total_abandon_cart' => $abandon,
            'total_complete_cart' => $complete,
            'abandon_rate' => $abandon / ($complete + $abandon),
            'average_cart_total' => $average / 100,
        ];
    }
}
