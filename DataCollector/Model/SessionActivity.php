<?php
declare(strict_types=1);

namespace Master\DataCollector\Model;

use Master\DataCollector\Model\ResourceModel\SessionActivity as ResourceModel;
use Magento\Framework\Model\AbstractModel;

/**
 * Class SessionActivity
 * @package Master\DataCollector\Model
 */
class SessionActivity extends AbstractModel
{
    const TABLE_NAME = 'master_session_activity';
    const CACHE_TAG = 'master_session_activity';

    /**
     * @var string
     */
    protected $_cacheTag = 'master_session_activity';

    /**
     * @var string
     */
    protected $_eventPrefix = 'master_session_activity';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
