<?php
declare(strict_types=1);

namespace Master\DataCollector\Model\Source;

/**
 * Class Device
 * @package Master\DataCollector\Model\Source
 */
class Device
{
    const TYPE_DESKTOP = 1;
    const TYPE_MOBILE = 2;
    const TYPE_TABLET = 3;
    const TYPES = ['desktop', 'mobile', 'tablet'];
}
