<?php
declare(strict_types=1);

namespace Master\DataCollector\Model;

use Magento\Framework\Model\AbstractModel;
use Master\DataCollector\Model\ResourceModel\ReviewLog as ResourceModel;

/**
 * Class ReviewLog
 * @package Master\DataCollector\Model
 */
class ReviewLog extends AbstractModel
{
    const TABLE_NAME = 'master_review_log';
    const CACHE_TAG = 'master_review_log';

    /**
     * @var string
     */
    protected $_cacheTag = 'master_review_log';

    /**
     * @var string
     */
    protected $_eventPrefix = 'master_review_log';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
