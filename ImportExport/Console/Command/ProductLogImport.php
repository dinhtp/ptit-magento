<?php
declare(strict_types=1);

namespace Master\ImportExport\Console\Command;

use Exception;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ProductLogImport
 * @package Master\ImportExport\Console\Command
 */
class ProductLogImport extends Command
{
    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * ProductLogImport constructor.
     * @param ResourceConnection $resource
     * @param string|null $name
     */
    public function __construct(
        ResourceConnection $resource,
        string $name = null
    ) {
        $this->resource = $resource;
        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('master:product:import');
        $this->setDescription('Import Product Log Data');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws LocalizedException
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output) :int
    {
        $table = $this->resource->getTableName('master_product_log');
        $connection = $this->resource->getConnection();

        $connection->truncateTable($table);

        for ($i = 1; $i <= 2000; $i++) {
            $connection->insertMultiple($table, $this->prepareData($i));
        }

        return Cli::RETURN_SUCCESS;
    }

    /**
     * @param int $id
     * @return bool
     */
    protected function isAttractiveProduct($id) : bool
    {
        return $id <= 23 || ($id >= 36 && $id <= 44) || ($id >= 47 && $id <= 52) || $id === 45;
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    protected function prepareData($id) :array
    {
        $attractive = $this->isAttractiveProduct($id);
        $totalComplete = $attractive ? random_int(1600, 2000) : random_int(100, 500);
        $totalAbandon = $attractive ? random_int(100, 400) : random_int(1400, 1800);
        $totalPresence = $totalComplete + $totalAbandon;

        return [
            'product_id' => $id,
            'total_cart_presence' => $totalPresence,
            'total_complete' => $totalComplete,
            'total_abandon' => $totalAbandon,
            'abandon_rate' => $totalAbandon / $totalPresence,
            'complete_rate' => $totalComplete / $totalPresence,
        ];
    }
}
