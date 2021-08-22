<?php
declare(strict_types=1);

namespace Master\DataCollector\Model\ResourceModel\ProductLog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Master\DataCollector\Model\ResourceModel\ProductLog as ResourceModel;
use Master\DataCollector\Model\ProductLog as Model;

/**
 * Class Collection
 * @package Master\DataCollector\Model\ResourceModel\ProductLog
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'master_product_log_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'master_product_log_collection';

    /**
     * @var string
     */
    protected $_mainTable = 'master_product_log';

    /**
     * Initialization here
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
