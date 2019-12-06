<?php

namespace PennyAppeal\SmartDebit;

use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Api
 * @package PennyAppeal\SmartDebit
 */
class Api
{
    protected $client;
    protected $pslId;
    protected $debug = false;

    /**
     * Api constructor.
     * @param string $host the domain to connect to, e.g. 'https://secure.ddprocessing.co.uk'
     * @param string $user your SmartDebit username
     * @param string $password your SmartDebit password
     * @param string $pslId your SmartDebit pslId
     * @param string $userAgent the name of your application, e.g. 'My backend iDD Client'
     */
    public function __construct(string $host, string $user, string $password, string $pslId, string $userAgent)
    {
        $this->pslId = $pslId;
        $this->client = new Client([
            'base_uri' => $host,
            'auth' => [$user, $password, 'basic'],
            'http_errors' => false,
            'synchronous' => true,
            'headers' => ['User-Agent' => $userAgent, 'Accept' => 'application/XML'],
        ]);
    }

    /**
     * Sets a debug flag used in api calls. When true, debug output is sent to STDOUT. $debug can also be a stream
     * handle (as returned by fopen()), in which case debug output will be written to the stream.
     * @param mixed $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * @param $path
     * @param $params
     * @return ResponseInterface
     * @throws ConnectException if guzzle can't connect to the host
     */
    protected function get($path, $params = null)
    {
        $options = [
            'debug' => $this->debug,
        ];
        if (!is_null($params)) {
            $options['query'] = $params;
        }
        return $this->client->get($path, $options);
    }

    /**
     * @param DateTime $date
     * @return ResponseInterface
     * @throws ConnectException if guzzle can't connect to the host
     */
    public function getSuccessfulCollectionReport(DateTime $date) : ResponseInterface
    {
        $params = [
            'query[service_user][pslid]' => $this->pslId,
            'query[collection_date]' => $date->format('Y-m-d'),
        ];
        return $this->get('/api/get_successful_collection_report', $params);
    }

    public function systemStatus()
    {
        return $this->get('/api/system_status');
    }
}
