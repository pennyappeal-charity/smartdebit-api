<?php

use PennyAppeal\SmartDebit\Api;

include "bootstrap.php";

function usage()
{
    throw new Exception("\nUsage: php " . __FILE__ . " <import_id>\n");
}

if ($argc != 2) {
    usage();
}
$importId = (int)$argv[1];

$api = new Api($host, $user, $pass, $pslId, $agent);
$response = $api->indemnity($importId);
dumpStatusCode($response);
$xmlData = $response->getBody()->getContents();
dumpContents($xmlData);

$xml = simplexml_load_string($xmlData);
if (isset($xml->file)) {
    echo(
        "Base 64 decoded <file> element:\n"
        . base64_decode($xml->file)
        . "\n"
    );
} else {
    echo("No <file> element found!\n");
}
