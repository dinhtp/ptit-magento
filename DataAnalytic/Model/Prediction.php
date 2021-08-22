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
        $client->get($this->getAnalyticServiceUrl());

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
        $data = json_encode(['sessionId' => $sessionId]);
        $client->setHeaders(['Content-Type' => 'application/json', 'Content-Length' => strlen($data)]);
        $client->post($this->getAnalyticServiceUrl(), $data);

        return [
            'status' => $client->getStatus(),
            'body' => $client->getBody()
        ];
    }

    /**
     * @return string
     */
    public function getAnalyticServiceUrl()
    {
        return getenv('ANALYTIC_SERVICE');
    }
}
