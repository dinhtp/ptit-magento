<?php
declare(strict_types=1);

namespace Master\DataCollector\Model;

use Magento\Framework\Model\AbstractModel;
use Master\DataCollector\Model\ResourceModel\ActivityLog as ResourceModel;

/**
 * Class ActivityLog
 * @package Master\DataCollector\Model
 */
class ActivityLog extends AbstractModel
{
    const TABLE_NAME = 'master_activity_log';
    const CACHE_TAG = 'master_activity_log';

    /**
     * @var string
     */
    protected $_cacheTag = 'master_activity_log';

    /**
     * @var string
     */
    protected $_eventPrefix = 'master_activity_log';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
