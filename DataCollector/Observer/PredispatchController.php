<?php
declare(strict_types=1);

namespace Master\DataCollector\Observer;

use Exception;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Request\Http;
use Master\DataCollector\Helper\Logger as HelperLogger;

/**
 * Class PredispatchController
 * @package Master\DataCollector\Observer
 */
class PredispatchController implements ObserverInterface
{
    /**
     * @var HelperLogger
     */
    protected $helperLogger;

    /**
     * PredispatchController constructor.
     * @param HelperLogger $helperLogger
     */
    public function __construct(
        HelperLogger $helperLogger
    ) {
        $this->helperLogger = $helperLogger;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws Exception
     */
    public function execute(Observer $observer)
    {
        /** @var Http $request */
        $request = $observer->getData('request');
        $saleSession = $this->helperLogger->getSaleSession();

        if (!$saleSession->getId()) {
            return;
        }

        $this->helperLogger->logActivity($saleSession->getId(), $request->getFullActionName(), $request->getMethod());
        $this->helperLogger->logSession($saleSession);
    }
}
