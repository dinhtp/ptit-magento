<?php
declare(strict_types=1);

namespace Master\DataCollector\Model;

use Magento\Framework\Model\AbstractModel;
use Master\DataCollector\Model\ResourceModel\ProductLog as ResourceModel;

/**
 * Class ProductLog
 * @package Master\DataCollector\Model
 */
class ProductLog extends AbstractModel
{
    const TABLE_NAME = 'master_product_log';
    const CACHE_TAG = 'master_product_log';

    /**
     * @var string
     */
    protected $_cacheTag = 'master_product_log';

    /**
     * @var string
     */
    protected $_eventPrefix = 'master_product_log';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
