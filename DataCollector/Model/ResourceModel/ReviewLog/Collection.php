<?php
declare(strict_types=1);

namespace Master\DataCollector\Model\ResourceModel\ReviewLog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Master\DataCollector\Model\ResourceModel\ReviewLog as ResourceModel;
use Master\DataCollector\Model\ReviewLog as Model;

/**
 * Class Collection
 * @package Master\DataCollector\Model\ResourceModel\ReviewLog
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
    protected $_eventPrefix = 'master_review_log_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'master_review_log_collection';

    /**
     * @var string
     */
    protected $_mainTable = 'master_review_log';

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
