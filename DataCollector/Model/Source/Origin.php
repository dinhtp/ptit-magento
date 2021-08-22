<?php
declare(strict_types=1);

namespace Master\DataCollector\Model\Source;

/**
 * Class Origin
 * @package Master\DataCollector\Model\Source
 */
class Origin
{
    const SOURCE_ADVERT = 1;
    const SOURCE_DIRECT = 2;
    const SOURCE_REFERRAL = 3;
    const SOURCE_SOCIAL = 4;
    const SOURCE_REDIRECT = 5;
    const SOURCE_OTHER = 6;
    const SOURCES = [1, 2, 3, 4, 5, 6];
}
