<?php

use PennyAppeal\SmartDebit\Api;

include "bootstrap.php";

$addacId = $app->getBacsIdArg();
$api = new Api($app->getHost(), $app->getUser(), $app->getPass(), $app->getPslId(), $app->getAgent());
$response = $api->addac($addacId);
$app->dumpStatusCode($response);
$xmlData = $response->getBody()->getContents();
$app->dumpContents($xmlData);
$app->dumpFileElement($xmlData);
