<?php
declare(strict_types=1);

namespace Master\DataCollector\Model\ResourceModel\SaleSession;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Master\DataCollector\Model\ResourceModel\SaleSession as ResourceModel;
use Master\DataCollector\Model\SaleSession as Model;

/**
 * Class Collection
 * @package Master\DataCollector\Model\ResourceModel\SaleSession
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
    protected $_eventPrefix = 'master_sale_session_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'master_sale_session_collection';

    /**
     * @var string
     */
    protected $_mainTable = 'master_sale_session';

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
