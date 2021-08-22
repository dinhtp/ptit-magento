<?php
declare(strict_types=1);

namespace Master\ImportExport\Console\Command;

use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ReviewLogImport
 * @package Master\ImportExport\Console\Command
 */
class ReviewLogImport extends AbstractImport
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('master:review:import');
        $this->setDescription('Import Review Log Data');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output) :int
    {
        $table = $this->resource->getTableName('master_review_log');
        $connection = $this->resource->getConnection();

        $connection->truncateTable($table);
        $totalCustomer = $this->getTotalCustomer();
        for ($i = 1; $i <= $totalCustomer; $i++) {
            $connection->insertMultiple($table, $this->prepareData($i));
        }

        return Cli::RETURN_SUCCESS;
    }

    /**
     * @param int $id
     * @return array
     * @throws \Exception
     */
    protected function prepareData($id) :array
    {
        $type = $this->getCustomerType($id);

        $total_rating = random_int(0, 8);
        if ($type === self::TYPE_ACTIVE) {
            $total_rating = random_int(45, 100);
        }
        if ($type === self::TYPE_REGULAR) {
            $total_rating = random_int(10, 42);
        }

        $totalReview = random_int(0, 8);
        if ($type === self::TYPE_ACTIVE) {
            $totalReview = random_int(45, 100);
        }
        if ($type === self::TYPE_REGULAR) {
            $totalReview = random_int(10, 42);
        }

        $ratingScore = random_int(15, 30);
        if ($type === self::TYPE_ACTIVE) {
            $ratingScore = random_int(30, 50);
        }
        if ($type === self::TYPE_REGULAR) {
            $ratingScore = random_int(20, 40);
        }

        $reviewLength = random_int(0, 140);
        if ($type === self::TYPE_ACTIVE) {
            $reviewLength = random_int(150, 200);
        }
        if ($type === self::TYPE_REGULAR) {
            $reviewLength = random_int(100, 180);
        }

        if ($total_rating === 0) {
            $ratingScore = 0;
        }
        if ($totalReview === 0) {
            $reviewLength = 0;
        }

        return [
            'customer_id' => $id,
            'total_rating' => $total_rating,
            'total_review' => $totalReview,
            'average_rating_score' => $ratingScore / 10,
            'average_review_length' => $reviewLength / 10,
        ];
    }

}
