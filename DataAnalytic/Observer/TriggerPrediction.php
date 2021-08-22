<?php
declare(strict_types=1);

namespace Master\DataAnalytic\Observer;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

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
     * CheckoutSuccess constructor.
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        CheckoutSession $checkoutSession
    ) {
        $this->checkoutSession = $checkoutSession;

    }

    /**
     * @param Observer $observer
     * @return void
     * @throws Exception
     */
    public function execute(Observer $observer)
    {
        $quoteId = (int) $this->checkoutSession->getQuoteId();
        $sessionId = (int) $this->checkoutSession->getSaleSessionId();

        if (!$quoteId || $sessionId) {
            return;
        }


    }
}
