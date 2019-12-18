<?php

use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface;

include '../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required(['SD_HOST', 'SD_USER', 'SD_PASS', 'SD_PSLID', 'SD_AGENT']);

$host = getenv('SD_HOST');
$user = getenv('SD_USER');
$pass = getenv('SD_PASS');
$pslId = getenv('SD_PSLID');
$agent = getenv('SD_AGENT');

echo("SD_HOST: {$host}\nSD_USER: {$user}\nSD_PASS: {$pass}\nSD_PSLID: {$pslId}\nSD_AGENT: {$agent}\n");

function dumpStatusCode(ResponseInterface $response)
{
    echo("Response status code = {$response->getStatusCode()}\n");
}

function dumpContents(string $contents)
{
    echo("Response body =\n{$contents}\n");
}

function dumpApiResponse(ResponseInterface $response)
{
    dumpStatusCode($response);
    dumpContents($response->getBody()->getContents());
}
