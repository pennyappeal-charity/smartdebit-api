<?php

use PennyAppeal\SmartDebit\Api;

include "bootstrap.php";

$aruddId = $app->getImportIdArg();
$api = new Api($app->getHost(), $app->getUser(), $app->getPass(), $app->getPslId(), $app->getAgent());
$response = $api->arudd($aruddId);
$app->dumpStatusCode($response);
$xmlData = $response->getBody()->getContents();
$app->dumpContents($xmlData);

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
