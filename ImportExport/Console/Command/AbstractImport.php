<?php
declare(strict_types=1);

namespace Master\ImportExport\Console\Command;

use Magento\Framework\App\ResourceConnection;
use Symfony\Component\Console\Command\Command;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;

/**
 * Class AbstractImport
 * @package Master\ImportExport\Console\Command
 */
abstract class AbstractImport extends Command
{
    const TYPE_ACTIVE = 'active';
    const TYPE_REGULAR = 'regular';
    const TYPE_ABANDON = 'abandon';

    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * @var CollectionFactory
     */
    protected $customerCollection;

    /**
     * AbstractImport constructor.
     * @param ResourceConnection $resource
     * @param CollectionFactory $customerCollection
     * @param string|null $name
     */
    public function __construct(
        ResourceConnection $resource,
        CollectionFactory $customerCollection,
        string $name = null
    ) {
        $this->resource = $resource;
        $this->customerCollection = $customerCollection;
        parent::__construct($name);
    }

    /**
     * @param $id
     * @return $this
     */
    protected function getCustomerType($id): string
    {
        if ($id <= 19 || ($id >= 101 && $id <= 120)) {
            return self::TYPE_ACTIVE;
        }

        if (($id >= 51 && $id <= 69) || ($id >= 121 && $id <= 169)) {
            return self::TYPE_REGULAR;
        }

        return self::TYPE_ABANDON;
    }

    /**
     * @return int
     */
    protected function getTotalCustomer(): int
    {
        return $this->customerCollection->create()->getSize();
    }

    /**
     * @param string $action
     * @return string
     */
    protected function mapActionName($action): array
    {
        $data = [
            'action' => ' ',
            'type' => 'GET',
        ];

        $getActions = [
            'view_account' => 'customer_account_index',
            'view_order' => 'sales_order_history',
            'view_wishlist' => 'wishlist_index_index',
            'view_downloadable_product' => 'downloadable_customer_products',
            'view_address' => 'customer_address_index',
            'view_stored_payment_methods' => 'vault_cards_listaction',
            'view_reviews' => 'review_customer_index',
            'view_product' => 'catalog_product_view',
            'view_category' => 'catalog_category_view',
            'view_cart' => 'checkout_cart_index',
            'search_product' => 'catalogsearch_result_index',
            'sort_product_list' => 'catalog_category_view',
            'advanced_filter_product_list' => 'catalog_category_view',
        ];

        $postAction = [
            'login' => 'customer_account_loginPost',
            'logout' => 'customer_account_logout',
            'subscribe_newsletter' => 'newsletter_subscriber_new',
            'send_inquiry' => 'contact_index_post',
            'update_cart' => 'checkout_cart_updatePost',
            'apply_coupon' => 'checkout_cart_couponPost',
            'checkout_cart' => 'checkout_onepage_success',
            'add_new_address' => 'customer_address_formPost',
            'add_product_to_cart' => 'checkout_cart_add',
            'add_product_to_wishlist' => 'wishlist_index_add',
            'add_product_rating' => 'review_product_post',
            'add_product_review' => 'review_product_post',
            'add_shipping_methods' => '/rest/default/V1/carts/mine/shipping-information',
            'add_payment_methods' => '/rest/default/V1/carts/mine/payment-information',
            'add_stored_payment_methods' => 'vault_cards_saveaction',
            'compare_product' => 'catalog_product_compare_add',
        ];

        if (isset($getActions[$action])) {
            $data['action'] = $getActions[$action];
            $data['type'] = 'GET';
        }

        if (isset($postAction[$action])) {
            $data['action'] = $postAction[$action];
            $data['type'] = 'POST';
        }

        return $data;
    }
}
