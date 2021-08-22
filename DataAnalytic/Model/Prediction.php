<?php
declare(strict_types=1);

namespace Master\DataAnalytic\Model;

use Magento\Framework\HTTP\Client\CurlFactory;

/**
 * Class Prediction
 * @package Master\DataAnalytic\Model
 */
class Prediction
{
    const ANALYTIC_URL = 'random-forest';

    /**
     * @var CurlFactory
     */
    protected $curl;

    /**
     * Prediction constructor.
     * @param CurlFactory $curl
     */
    public function __construct(
        CurlFactory $curl
    ) {
        $this->curl = $curl;
    }

    /**
     * @return array
     */
    public function checkConnection()
    {
        $client = $this->curl->create();
        $client->get(self::ANALYTIC_URL);

        return [
            'status' => $client->getStatus(),
            'body' => $client->getBody()
        ];
    }

    /**
     * @param int $sessionId
     * @return array
     */
    public function requestPrediction($sessionId)
    {
        $client = $this->curl->create();
        $client->post(self::ANALYTIC_URL, ['sessionId' => $sessionId]);

        return [
            'status' => $client->getStatus(),
            'body' => $client->getBody()
        ];
    }
}
