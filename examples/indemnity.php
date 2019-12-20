<?php

use PennyAppeal\SmartDebit\Api;

include "bootstrap.php";

$importId = $app->getBacsIdArg();
$api = new Api($app->getHost(), $app->getUser(), $app->getPass(), $app->getPslId(), $app->getAgent());
$response = $api->indemnity($importId);
$app->dumpStatusCode($response);
$xmlData = $response->getBody()->getContents();
$app->dumpContents($xmlData);
$app->dumpFileElement($xmlData);
