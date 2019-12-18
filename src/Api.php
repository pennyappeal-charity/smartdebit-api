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
    protected $variableKeys;

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
        $this->setVariableKeys();
    }

    protected function setVariableKeys()
    {
        $this->variableKeys = [
            'reference_number', // between 6 and 18 'valid' characters, unique for this account,
                                // start with alphanumeric, not start with 'DDIC', not start or end with a space,
                                // not consist of all the same character
            'frequency_type',   // W|M|Q|Y
            'sort_code',        // can be nnnnnn or nn-nn-nn, in the sandbox use 6 zeros for a successful response
            'account_number',   // 8 digits, 8 zeros will return an error in the sandbox
            'account_name',     // 3 to 18 characters
            'first_name',       // 1 to 32 characters
            'last_name',        // 1 to 32 characters
            'start_date',       // Y-m-d,
            'end_date',         // Y-m-d, optional
            'default_amount',   // integer, in pence
            'first_amount',     // integer, in pence, optional (uses default_amount if not present)
            'frequency_factor', // optional, can be 1, 2, 3 or 4, default is 1, collect every n frequency_types
            'title',            // 1 to 32 characters
            'email_address',    // 5 to 64 characters
            'company_name',     // 1 to 32 characters
            'payer_reference',  // 1 to 255 characters
            'address_1',        // 1 to 255 characters
            'address_2',        // 1 to 255 characters
            'address_3',        // 1 to 255 characters
            'town',             // 1 to 255 characters
            'county',           // 1 to 255 characters
            'postcode',         // 1 to 255 characters
            'country',          // 1 to 255 characters
            'support_gift_aid', // optional, true, false, 1 or 0, default false
            'promotion',        // 1 to 8 characters, probably optional?
        ];
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

    protected function post($path, $params = null)
    {
        $options = [
            'debug' => $this->debug,
        ];
        if (!is_null($params)) {
            $options['query'] = $params;
        }
        return $this->client->post($path, $options);
    }

    public function dataDump($payerReference = null, $format = 'XML')
    {
        $params = [
            'query[service_user][pslid]' => $this->pslId,
            'query[report_format]' => $format,
        ];
        if (!is_null($payerReference)) {
            $params['query[reference_number]'] = $payerReference;
        }
        return $this->post('/api/data/dump', $params);
    }

    protected function isVariableKey($key)
    {
        return in_array($key, $this->variableKeys);
    }

    protected function ddiVariableParams(array $data)
    {
        $params = [
            'variable_ddi[service_user][pslid]' => $this->pslId,
        ];
        foreach ($data as $key => $value) {
            if ($this->isVariableKey($key)) {
                $params["variable_ddi[{$key}]"] = $value;
            } elseif ($key == 'debits') {
                // @todo add multiple initial collections
            }
        }
        return $params;
    }

    public function ddiVariableValidate(array $data)
    {
        $params = $this->ddiVariableParams($data);
        return $this->post('/api/ddi/variable/validate', $params);
    }

    public function ddiVariableCreate(array $data)
    {
        $params = $this->ddiVariableParams($data);
        return $this->post('/api/ddi/variable/create', $params);
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

    public function indemnity($importId)
    {
        $importId = (int)$importId;
        $params = [
            'query[service_user][pslid]' => $this->pslId,
        ];
        return $this->get("/api/indemnity/{$importId}", $params);
    }

    public function indemnityList(
        DateTime $fromDate = null,
        DateTime $toDate = null,
        $maxResults = 100,
        $startIndex = 0,
        $idFrom = null
    ) {
        $params = [
            'query[service_user][pslid]' => $this->pslId,
            'query[max_results]' => $maxResults,
        ];
        if (!is_null($fromDate)) {
            $params['query[from_date]'] = $fromDate->format('Y-m-d');
        }
        if (!is_null($toDate)) {
            $params['query[till_date]'] = $toDate->format('Y-m-d');
        }
        if ($startIndex != 0) {
            $params['query[start_index]'] = $startIndex;
        }
        if (!is_null($idFrom)) {
            $params['id_from'] = $idFrom;
        }
        return $this->post('/api/indemnity/list', $params);
    }

    public function systemStatus()
    {
        return $this->get('/api/system_status');
    }
}
