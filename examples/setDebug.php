<?php

include "bootstrap.php";

use PennyAppeal\SmartDebit\Api;

$api = new Api($app->getHost(), $app->getUser(), $app->getPass(), $app->getPslId(), $app->getAgent());

$api->setDebug(true);
$app->dumpApiResponse(
    $api->systemStatus()
);
