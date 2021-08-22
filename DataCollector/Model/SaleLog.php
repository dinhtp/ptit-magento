<?php
declare(strict_types=1);

namespace Master\DataCollector\Model;

use Magento\Framework\Model\AbstractModel;
use Master\DataCollector\Model\ResourceModel\SaleLog as ResourceModel;

/**
 * Class SaleLog
 * @package Master\DataCollector\Model
 */
class SaleLog extends AbstractModel
{
    const TABLE_NAME = 'master_sale_log';
    const CACHE_TAG = 'master_sale_log';

    /**
     * @var string
     */
    protected $_cacheTag = 'master_sale_log';

    /**
     * @var string
     */
    protected $_eventPrefix = 'master_sale_log';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
