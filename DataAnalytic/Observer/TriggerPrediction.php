<?php
declare(strict_types=1);

namespace Master\DataAnalytic\Observer;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Master\DataAnalytic\Model\Prediction;

/**
 * Class TriggerPrediction
 * @package Master\DataAnalytic\Observer
 */
class TriggerPrediction implements ObserverInterface
{
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var Prediction
     */
    protected $prediction;

    /**
     * TriggerPrediction constructor.
     * @param CheckoutSession $checkoutSession
     * @param Prediction $prediction
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        Prediction $prediction
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->prediction = $prediction;

    }

    /**
     * @param Observer $observer
     * @return void
     * @throws Exception
     */
    public function execute(Observer $observer)
    {
        $quoteId = (int)$this->checkoutSession->getQuoteId();
        $sessionId = (int)$this->checkoutSession->getSaleSessionId();

        if ($quoteId && $sessionId) {
            $this->prediction->requestPrediction($sessionId);
        }
    }
}
