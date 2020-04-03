<?php

namespace PennyAppeal\SmartDebit;

use DateTime;
use Exception;
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

    protected function isVariableKey($key)
    {
        return in_array($key, $this->variableKeys);
    }

    /**
     * Check if the given parameter is a valid SmartDebit extended parameter - an array with 2 keys: amount and date
     * the amount must be an integer and the date must in the format Y-m-d
     * @param $data
     * @return bool
     */
    protected function isExtendedParameter($data)
    {
        if (is_array($data) && array_key_exists('amount', $data) && array_key_exists('date', $data)) {
            $date = date_create_from_format('Y-m-d', $data['date']);
            if ((ctype_digit($data['amount']) || is_int($data['amount'])) && $date !== false) {
                return true;
            }
        }
        return false;
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
            'regular_amount',   // integer, in pence
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

    /**
     * @param $path
     * @param null $params
     * @return ResponseInterface
     */
    protected function post($path, $params = null)
    {
        $options = [
            'debug' => $this->debug,
        ];
        if (!is_null($params)) {
            $options['form_params'] = $params;
        }
        return $this->client->post($path, $options);
    }

    protected function getBacsListParams(
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
        return $params;
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

    public function addac($addacId)
    {
        $addacId = (int)$addacId;
        $params = [
            'query[service_user][pslid]' => $this->pslId,
        ];
        return $this->get("/api/addac/{$addacId}", $params);
    }

    public function addacList(
        DateTime $fromDate = null,
        DateTime $toDate = null,
        $maxResults = 100,
        $startIndex = 0,
        $idFrom = null
    ) {
        $params = $this->getBacsListParams($fromDate, $toDate, $maxResults, $startIndex, $idFrom);
        return $this->post('/api/addac/list', $params);
    }

    public function aruddList(
        DateTime $fromDate = null,
        DateTime $toDate = null,
        $maxResults = 100,
        $startIndex = 0,
        $idFrom = null
    ) {
        $params = $this->getBacsListParams($fromDate, $toDate, $maxResults, $startIndex, $idFrom);
        return $this->post('/api/arudd/list', $params);
    }

    public function arudd($aruddId)
    {
        $aruddId = (int)$aruddId;
        $params = [
            'query[service_user][pslid]' => $this->pslId,
        ];
        return $this->get("/api/arudd/{$aruddId}", $params);
    }

    public function auddisList(
        DateTime $fromDate = null,
        DateTime $toDate = null,
        $maxResults = 100,
        $startIndex = 0,
        $idFrom = null
    ) {
        $params = $this->getBacsListParams($fromDate, $toDate, $maxResults, $startIndex, $idFrom);
        return $this->post('/api/auddis/list', $params);
    }

    public function auddis($auddisId)
    {
        $auddisId = (int)$auddisId;
        $params = [
            'query[service_user][pslid]' => $this->pslId,
        ];
        return $this->get("/api/auddis/{$auddisId}", $params);
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    protected function ddiVariableParams(array $data)
    {
        $params = [
            'variable_ddi[service_user][pslid]' => $this->pslId,
        ];
        foreach ($data as $key => $value) {
            if ($this->isVariableKey($key)) {
                $params["variable_ddi[{$key}]"] = $value;
            } elseif ($key == 'debits') {
                throw new Exception("Extended parameters are not implemented");
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

    public function ddiVariableUpdate(string $reference, array $data)
    {
        $params = $this->ddiVariableParams($data);
        $params['variable_ddi[reference_number]'] = $reference;
        return $this->post("/api/ddi/variable/{$reference}/update", $params);
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
        $params = $this->getBacsListParams($fromDate, $toDate, $maxResults, $startIndex, $idFrom);
        return $this->post('/api/indemnity/list', $params);
    }

    public function systemStatus()
    {
        return $this->get('/api/system_status');
    }

    /**
     * @param string $filename The name of the data being imported
     * @param string $csvData The actual data
     * @param string $action CREATE or UPDATE
     * @return ResponseInterface
     */
    public function payerFileAsyncImport(string $filename, string $csvData, string $action)
    {
        $params = [
            'payer_file_import[service_user][pslid]' => $this->pslId,
            'payer_file_import[file_name]' => $filename,
            'payer_file_import[file]' => base64_encode($csvData),
            'payer_file_import[action]' => $action,
        ];
        return $this->post('/api/payer_file/async_import', $params);
    }

    public function payerFileAsyncImportStatus($importId)
    {
        return $this->get("/api/payer_file/{$importId}");
    }
}
