<?php

namespace PennyAppeal\SmartDebitExample;

use Exception;

/**
 * Reads the name of JSON file from the command line arguments, loads the file and decodes it.
 * The JSON file that gets decoded when you instantiate this class should produce a PHP array containing the relevant
 * variable_ddi keys and values for making a call to /ddi/variable/validate and friends.
 * e.g. { "reference_number": "TEST-12345678", "frequency_type": "W", "sort_code": "000000", etc }
 */
class JsonFileLoader
{
    protected $jsonFile;
    protected $json;
    protected $decodedJson;

    public function __construct()
    {
        global $argc, $argv;

        $this->jsonFile = null;
        $argIndex = 1;
        while ($argIndex < $argc) {
            switch ($argv[$argIndex]) {
                case '-f':
                    $argIndex++;
                    $this->jsonFile = $argv[$argIndex];
                    $argIndex++;
                    break;
                default:
                    $this->usage();
            }
        }
        if (is_null($this->jsonFile)) {
            $this->usage();
        }

        $this->json = file_get_contents($this->jsonFile);
        $this->decodedJson = json_decode($this->json, true);
        if (is_null($this->decodedJson)) {
            $this->nullJson();
        }
    }

    /**
     * Display usage message and throw an exception
     * @throws Exception
     */
    protected function usage()
    {
        throw new Exception("\nUsage: php <php file> [-f <json filename>]\n");
    }

    /**
     * Display invalid json message and throw an exception
     * @throws Exception
     */
    protected function nullJson()
    {
        throw new Exception(
            "\nJSON file '{$this->jsonFile}' is not readable, is invalid JSON, or just contains null\n"
        );
    }

    public function getDecodedJson()
    {
        return $this->decodedJson;
    }
}
