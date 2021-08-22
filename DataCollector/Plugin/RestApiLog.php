<?php
declare(strict_types=1);

namespace Master\DataCollector\Plugin;

use Exception;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Webapi\Controller\Rest;
use Master\DataCollector\Helper\Logger as HelperLogger;

/**
 * Class RestApiLog
 * @package Master\DataCollector\Plugin
 */
class RestApiLog
{
    /**
     * @var HelperLogger
     */
    protected $helperLogger;

    /**
     * RestApiLog constructor.
     * @param HelperLogger $helperLogger
     */
    public function __construct(
        HelperLogger $helperLogger
    ) {
        $this->helperLogger = $helperLogger;
    }

    /**
     * @param Rest $subject
     * @param Http|RequestInterface $request
     * @return RequestInterface[]
     * @throws Exception
     */
    public function beforeDispatch(Rest $subject, RequestInterface $request): array
    {
        $saleSession = $this->helperLogger->getSaleSession();
        if (!$saleSession->getId()) {
            return [$request];
        }

        $this->helperLogger->logActivity($saleSession->getId(), $request->getFullActionName(), $request->getMethod());
        $this->helperLogger->logSession($saleSession);

        return [$request];
    }
}
