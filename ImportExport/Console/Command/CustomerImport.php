<?php
declare(strict_types=1);

namespace Master\ImportExport\Console\Command;

use Exception;
use Magento\Framework\App\Area;
use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Framework\App\State;
use Master\ImportExport\Model\Data\Customer as CustomerData;

/**
 * Class CustomerImport
 * @package Master\ImportExport\Console\Command
 */
class CustomerImport extends Command
{
    /**
     * @var AccountManagementInterface
     */
    protected $accountManagement;

    /**
     * @var CustomerData
     */
    protected $customerData;

    /**
     * @var State
     */
    protected $state;

    /**
     * CustomerImport constructor.
     * @param AccountManagementInterface $accountManagement
     * @param CustomerData $customerData
     * @param State $state
     * @param string|null $name
     */
    public function __construct(
        AccountManagementInterface $accountManagement,
        CustomerData $customerData,
        State $state,
        string $name = null
    ) {
        $this->accountManagement = $accountManagement;
        $this->customerData = $customerData;
        $this->state = $state;
        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('master:customer:import');
        $this->setDescription('Import Customer Data');
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
        $this->state->setAreaCode(Area::AREA_FRONTEND);

        for ($i = 1; $i <= 187; $i++) {
            $customer = $this->customerData->generateCustomer();
            $this->accountManagement->createAccount($customer);
        }

        return Cli::RETURN_SUCCESS;
    }
}
