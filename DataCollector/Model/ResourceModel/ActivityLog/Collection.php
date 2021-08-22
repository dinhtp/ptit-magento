<?php
declare(strict_types=1);

namespace Master\DataCollector\Model\ResourceModel\ActivityLog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Master\DataCollector\Model\ResourceModel\ActivityLog as ResourceModel;
use Master\DataCollector\Model\ActivityLog as Model;

/**
 * Class Collection
 * @package Master\DataCollector\Model\ResourceModel\ActivityLog
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
    protected $_eventPrefix = 'master_activity_log_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'master_activity_log_collection';

    /**
     * @var string
     */
    protected $_mainTable = 'master_activity_log';

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
