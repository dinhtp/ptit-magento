<?php
declare(strict_types=1);

namespace Master\DataCollector\Model;

use Magento\Framework\Model\AbstractModel;
use Master\DataCollector\Model\ResourceModel\CartLog as ResourceModel;

/**
 * Class CartLog
 * @package Master\DataCollector\Model
 */
class CartLog extends AbstractModel
{
    const TABLE_NAME = 'master_cart_log';
    const CACHE_TAG = 'master_cart_log';

    /**
     * @var string
     */
    protected $_cacheTag = 'master_cart_log';

    /**
     * @var string
     */
    protected $_eventPrefix = 'master_cart_log';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
