<?php

include "bootstrap.php";

use PennyAppeal\SmartDebit\Api;

$api = new Api($app->getHost(), $app->getUser(), $app->getPass(), $app->getPslId(), $app->getAgent());

$app->dumpApiResponse(
    $api->systemStatus()
);
