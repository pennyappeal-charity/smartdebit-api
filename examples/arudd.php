<?php

use PennyAppeal\SmartDebit\Api;

include "bootstrap.php";

$aruddId = $app->getBacsIdArg();
$api = new Api($app->getHost(), $app->getUser(), $app->getPass(), $app->getPslId(), $app->getAgent());
$response = $api->arudd($aruddId);
$app->dumpStatusCode($response);
$xmlData = $response->getBody()->getContents();
$app->dumpContents($xmlData);
$app->dumpFileElement($xmlData);
