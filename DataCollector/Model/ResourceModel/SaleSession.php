<?php
declare(strict_types=1);

namespace Master\DataCollector\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Master\DataCollector\Model\SaleSession as Model;

/**
 * Class SaleSession
 * @package Master\DataCollector\Model\ResourceModel
 */
class SaleSession extends AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Model::TABLE_NAME, 'id');
    }
}
