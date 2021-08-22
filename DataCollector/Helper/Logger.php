<?php
declare(strict_types=1);

namespace Master\DataCollector\Helper;

use Exception;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Master\DataCollector\Model\ResourceModel\SessionActivity\CollectionFactory as ActivityCollection;
use Master\DataCollector\Model\SaleSession;
use Master\DataCollector\Model\SaleSessionFactory;
use Master\DataCollector\Model\SessionActivity;
use Master\DataCollector\Model\SessionActivityFactory;
use Master\DataCollector\Model\Source\Device;
use Master\DataCollector\Model\Source\Origin;
use Magento\Quote\Model\QuoteFactory;

/**
 * Class Logger
 * @package Master\DataCollector\Helper
 */
class Logger
{
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var SaleSessionFactory
     */
    protected $saleSession;

    /**
     * @var SessionActivityFactory
     */
    protected $sessionActivity;

    /**
     * @var ActivityCollection
     */
    protected $activityCollection;

    /**
     * @var QuoteFactory
     */
    protected $quoteFactory;

    /**
     * Logger constructor.
     * @param CheckoutSession $checkoutSession
     * @param SaleSessionFactory $saleSession
     * @param SessionActivityFactory $sessionActivity
     * @param CustomerSession $customerSession
     * @param ActivityCollection $activityCollection
     * @param QuoteFactory $quoteFactory
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        SaleSessionFactory $saleSession,
        SessionActivityFactory $sessionActivity,
        CustomerSession $customerSession,
        ActivityCollection $activityCollection,
        QuoteFactory $quoteFactory
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->saleSession = $saleSession;
        $this->sessionActivity = $sessionActivity;
        $this->customerSession = $customerSession;
        $this->activityCollection = $activityCollection;
        $this->quoteFactory = $quoteFactory;
    }
    /**
     * @param SaleSession $saleSession
     * @return SaleSession
     * @throws Exception
     */
    public function logSession($saleSession): SaleSession
    {
        $activityCol = $this->activityCollection->create();
        $cartId = (int) $this->checkoutSession->getQuoteId();
        $customerId = (int) $this->customerSession->getCustomerId();

        $view = 0;
        $productView = 0;
        $cartView = 0;
        $categoryView = 0;
        $searchView = 0;
        $isCheckout = 0;

        $activities = $activityCol->addFieldToFilter('sale_session_id', $saleSession->getId())->setOrder('created_at', 'ASC');
        $totalActivities = $activityCol->getSize();

        $firstTime = strtotime($activityCol->getFirstItem()->getData('created_at'));
        $lastTime = strtotime($activityCol->getLastItem()->getData('created_at'));
        $average_interval = ($lastTime - $firstTime) > 0 ? ($lastTime - $firstTime) / $totalActivities : 0;

        foreach ($activities as $activity) {
            $actionName = $activity->getData('action_name');

            switch ($actionName) {
                case 'catalog_category_view':
                    $categoryView++;
                    break;
                case 'catalog_product_view':
                    $productView++;
                    break;
                case 'catalogsearch_result_index':
                    $searchView++;
                    break;
                case 'checkout_cart_index':
                    $cartView++;
                    break;
            }

            if ($activity->getData('request_type') === 'GET') {
                $view++;
            }

            if ($actionName === 'checkout_onepage_success') {
                $isCheckout = 1;
            }
        }

        $saleSession->setData('total_product_view', $productView)
            ->setData('total_cart_view', $cartView)
            ->setData('total_category_view', $categoryView)
            ->setData('total_search', $searchView)
            ->setData('total_view', $view)
            ->setData('average_interval', $average_interval);

        if ($customerId) {
            $saleSession->setData('customer_id', (int) $this->customerSession->getCustomerId());
        }

        if ($isCheckout) {
            $saleSession->setData('is_checkout_cart', $isCheckout);
        }

        if ($cartId) {
            $cart = $this->quoteFactory->create()->load($cartId);
            $saleSession->setData('cart_id', $cartId);
            $saleSession->setData('total_item_qty', $cart->getItemsQty());
            $saleSession->setData('total_cart_value', $cart->getGrandTotal());
        }

        $saleSession->save();
        return $saleSession;
    }

    /**
     * @param int $sessionId
     * @param string $actionName
     * @param string $requestMethod
     * @return SessionActivity
     * @throws Exception
     */
    public function logActivity($sessionId, $actionName, $requestMethod): SessionActivity
    {
        $newActivity = $this->sessionActivity->create();

        $newActivity->setData('sale_session_id', $sessionId)
            ->setData('action_name', $actionName)
            ->setData('request_type', $requestMethod)
            ->save();

        return $newActivity;
    }

    /**
     * @return SaleSession
     * @throws Exception
     */
    public function getSaleSession(): SaleSession
    {
        $currentId = $this->checkoutSession->getSaleSessionId();
        if ($currentId === null || $currentId === '') {
            $newSession = $this->saleSession->create();
            $newSession->setData('origin', Origin::SOURCE_DIRECT)
                ->setData('device_type', Device::TYPE_DESKTOP)
                ->save();

            $this->checkoutSession->setSaleSessionId($newSession->getId());

            return $newSession;
        }

        return $this->saleSession->create()->load($currentId);
    }
}
