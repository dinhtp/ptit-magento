<?php
declare(strict_types=1);

namespace Master\DataAnalytic\Console\Command;

use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Master\DataAnalytic\Model\Prediction;

/**
 * Class Trigger
 * @package Master\DataAnalytic\Console\Command
 */
class Trigger extends Command
{
    /**
     * @var Prediction
     */
    protected $prediction;

    /**
     * Trigger constructor.
     * @param Prediction $prediction
     * @param string|null $name
     */
    public function __construct(
        Prediction $prediction,
        string $name = null
    ) {
        $this->prediction = $prediction;
        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $options = [
            new InputOption(
                'session_id',
                null,
                InputOption::VALUE_OPTIONAL,
                'Sale Session Id'
            ),
        ];

        $this->setName('master:analytic:trigger');
        $this->setDescription('Trigger Prediction For Sale Session');
        $this->setDefinition($options);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sessionId = (int)$input->getOption('session_id');

        if (!$sessionId) {
            $result = $this->prediction->checkConnection();

            $output->write(sprintf('Status: %d', $result['status']), true);
            $output->write(sprintf('Body: %s', $result['body']), true);

            return Cli::RETURN_SUCCESS;
        }

        $result = $this->prediction->requestPrediction($sessionId);

        $output->write(sprintf('Status: %d', $result['status']), true);
        $output->write(sprintf('Body: %s', $result['body']), true);

        return Cli::RETURN_SUCCESS;
    }
}
