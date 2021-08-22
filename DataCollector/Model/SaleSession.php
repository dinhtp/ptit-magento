<?php
declare(strict_types=1);

namespace Master\DataCollector\Model;

use Magento\Framework\Model\AbstractModel;
use Master\DataCollector\Model\ResourceModel\SaleSession as ResourceModel;

/**
 * Class SaleSession
 * @package Master\DataCollector\Model
 */
class SaleSession extends AbstractModel
{
    const TABLE_NAME = 'master_sale_session';
    const CACHE_TAG = 'master_sale_session';

    /**
     * @var string
     */
    protected $_cacheTag = 'master_sale_session';

    /**
     * @var string
     */
    protected $_eventPrefix = 'master_sale_session';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
