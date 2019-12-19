<?php

use PennyAppeal\SmartDebit\Api;
use PennyAppeal\SmartDebitExample\JsonFileLoader;

include "bootstrap.php";

$loader = new JsonFileLoader();
$api = new Api($app->getHost(), $app->getUser(), $app->getPass(), $app->getPslId(), $app->getAgent());
$app->dumpApiResponse(
    $api->ddiVariableValidate($loader->getDecodedJson())
);
