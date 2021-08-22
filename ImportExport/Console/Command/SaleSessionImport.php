<?php
declare(strict_types=1);

namespace Master\ImportExport\Console\Command;

use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Console\Cli;
use Magento\Framework\File\Csv;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Master\DataCollector\Model\SaleSessionFactory;
use Master\DataCollector\Model\Source\Device;
use Master\DataCollector\Model\Source\Origin;

/**
 * Class SaleSessionImport
 * @package Master\ImportExport\Console\Command
 */
class SaleSessionImport extends AbstractImport
{
    /**
     * @var Csv
     */
    protected $csv;

    /**
     * @var SaleSessionFactory
     */
    protected $saleSession;

    /**
     * SaleSessionImport constructor.
     * @param ResourceConnection $resource
     * @param CollectionFactory $customerCollection
     * @param Csv $csv
     * @param SaleSessionFactory $saleSession
     * @param string|null $name
     */
    public function __construct(
        ResourceConnection $resource,
        CollectionFactory $customerCollection,
        Csv $csv,
        SaleSessionFactory $saleSession,
        string $name = null
    ) {
        $this->csv = $csv;
        $this->saleSession = $saleSession;
        parent::__construct($resource, $customerCollection, $name);
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('master:sale_session:import');
        $this->setDescription('Import Sale Session Data');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connection = $this->resource->getConnection();
        $sessions = $this->csv->getData(BP . '/var/sample/session_data.csv');

        $saleSessionTable = $this->resource->getTableName('master_sale_session');
        $sessionActivityTable = $this->resource->getTableName('master_session_activity');

//        $connection->truncateTable($saleSessionTable);
//        $connection->truncateTable($sessionActivityTable);

        $activities = [];
        $actionString = '';
        foreach ($sessions as $key => $ses) {
            if ($key === 0) {
                continue;
            }

            $cusId = (int)$ses[0];
            $action = trim($ses[2]);

            $actionString .= $action;
            $activities[] = $this->prepareSessionActivityData(0, $action);

            if (!isset($sessions[$key + 1]) || (isset($sessions[$key + 1]) && $sessions[$key + 1][1] !== $ses[1])) {
                $createdID = $this->createSessionData($cusId, $actionString);

                foreach ($activities as $idx => $activity) {
                    $activities[$idx]['sale_session_id'] = $createdID;
                }

                $connection->insertMultiple($sessionActivityTable, $activities);

                $activities = [];
                $actionString = '';
            }
        }

        return Cli::RETURN_SUCCESS;
    }

    /**
     * @param int $id
     * @param string $actionString
     * @return int
     * @throws \Exception
     */
    protected function createSessionData($id, $actionString) :int
    {
        $type = $this->getCustomerType($id);
        $saleSession = $this->saleSession->create();

        $origin = Origin::SOURCES[(int) array_rand(Origin::SOURCES)];
        if ($type === self::TYPE_ACTIVE) {
            $activeOrigin = [Origin::SOURCE_DIRECT, Origin::SOURCE_SOCIAL, Origin::SOURCE_REDIRECT];
            $origin = $activeOrigin[(int) array_rand($activeOrigin)];
        }
        if ($type === self::TYPE_REGULAR) {
            $origin = Origin::SOURCES[(int) array_rand(Origin::SOURCES)];
        }

        $device = Device::TYPES[(int) array_rand(Device::TYPES)];
        if ($type === self::TYPE_ACTIVE) {
            $activeDevice = [Device::TYPE_DESKTOP, Device::TYPE_TABLET];
            $device = $activeDevice[(int) array_rand($activeDevice)];
        }
        if ($type === self::TYPE_REGULAR) {
            $device = Device::TYPES[(int) array_rand(Device::TYPES)];
        }

        $interval = random_int(1100, 5000);
        if ($type === self::TYPE_ACTIVE) {
            $interval = random_int(400, 2400);
        }
        if ($type === self::TYPE_REGULAR) {
            $interval = random_int(500, 5000);
        }

        $productView = random_int(5, 50);
        if ($type === self::TYPE_ACTIVE) {
            $productView = random_int(15, 30);
        }
        if ($type === self::TYPE_REGULAR) {
            $productView = random_int(7, 25);
        }

        $cartView = random_int(7, 20);
        if ($type === self::TYPE_ACTIVE) {
            $cartView = random_int(3, 8);
        }
        if ($type === self::TYPE_REGULAR) {
            $cartView = random_int(1, 10);
        }

        $cateView = random_int(5, 50);
        if ($type === self::TYPE_ACTIVE) {
            $cateView = random_int(15, 30);
        }
        if ($type === self::TYPE_REGULAR) {
            $cateView = random_int(7, 25);
        }

        $search = random_int(8, 20);
        if ($type === self::TYPE_ACTIVE) {
            $search = random_int(4, 11);
        }
        if ($type === self::TYPE_REGULAR) {
            $search = random_int(2, 15);
        }

        $itemQty = random_int(1, 20);
        if ($type === self::TYPE_ACTIVE) {
            $itemQty = random_int(1, 11);
        }
        if ($type === self::TYPE_REGULAR) {
            $itemQty = random_int(1, 5);
        }

        $cartValue = random_int(500, 50000);
        if ($type === self::TYPE_ACTIVE) {
            $cartValue = random_int(7000, 80000);
        }
        if ($type === self::TYPE_REGULAR) {
            $cartValue = random_int(700, 20000);
        }

        $isCheckoutCart = 0;
        if (strpos($actionString, 'checkout_cart') !== false) {
            $isCheckoutCart = 1;
            $interval = random_int(300, 1400);
        }

        $saleSession->setData('customer_id', $id)
            ->setData('cart_id', random_int(1, 999))
            ->setData('device_type', $device)
            ->setData('origin', $origin)
            ->setData('average_interval', $interval / 100)
            ->setData('total_view', $productView + $cartView + $cateView + $search)
            ->setData('total_product_view', $productView)
            ->setData('total_cart_view', $cartView)
            ->setData('total_category_view', $cateView)
            ->setData('total_search', $search)
            ->setData('total_item_qty', $itemQty)
            ->setData('total_cart_value', $cartValue / 100)
            ->setData('is_checkout_cart', $isCheckoutCart)
            ->save();

        return (int) $saleSession->getId();
    }

    /**
     * @param int $id
     * @param string $name
     * @return array
     */
    protected function prepareSessionActivityData($id, $name): array
    {
        $actionData = $this->mapActionName($name);

        return [
            'sale_session_id' => $id,
            'action_name' => $actionData['action'],
            'request_type' => $actionData['type'],
        ];
    }
}
