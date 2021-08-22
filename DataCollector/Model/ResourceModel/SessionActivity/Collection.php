<?php
declare(strict_types=1);

namespace Master\DataCollector\Model\ResourceModel\SessionActivity;

use Master\DataCollector\Model\SessionActivity as Model;
use Master\DataCollector\Model\ResourceModel\SessionActivity as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Master\DataCollector\Model\ResourceModel\SessionActivity
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
    protected $_eventPrefix = 'master_session_activity_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'master_session_activity_collection';

    /**
     * @var string
     */
    protected $_mainTable = 'master_session_activity';

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
