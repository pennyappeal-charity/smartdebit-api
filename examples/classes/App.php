<?php

namespace PennyAppeal\SmartDebitExample;

use Psr\Http\Message\ResponseInterface;

class App
{
    use DataDump;
    use GetSuccessfulCollectionReport;
    use Indemnity;
    use IndemnityList;

    protected $argc;
    protected $argv;
    protected $host;
    protected $user;
    protected $pass;
    protected $pslId;
    protected $agent;

    public function __construct($argc, $argv)
    {
        $this->argc = $argc;
        $this->argv = $argv;
        $this->host = getenv('SD_HOST');
        $this->user = getenv('SD_USER');
        $this->pass = getenv('SD_PASS');
        $this->pslId = getenv('SD_PSLID');
        $this->agent = getenv('SD_AGENT');
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function getPslId()
    {
        return $this->pslId;
    }

    public function getAgent()
    {
        return $this->agent;
    }

    public function dumpCredentials()
    {
        echo(
            "SD_HOST: {$this->host}\nSD_USER: {$this->user}\nSD_PASS: {$this->pass}\nSD_PSLID: {$this->pslId}\n"
            . "SD_AGENT: {$this->agent}\n"
        );
    }

    public function dumpStatusCode(ResponseInterface $response)
    {
        echo("Response status code = {$response->getStatusCode()}\n");
    }

    public function dumpContents(string $contents)
    {
        echo("Response body =\n{$contents}\n");
    }

    public function dumpApiResponse(ResponseInterface $response)
    {
        $this->dumpStatusCode($response);
        $this->dumpContents($response->getBody()->getContents());
    }
}
