<?php

use PennyAppeal\SmartDebit\Api;

include "bootstrap.php";

$date = $app->getDateArg();
$api = new Api($app->getHost(), $app->getUser(), $app->getPass(), $app->getPslId(), $app->getAgent());
$app->dumpApiResponse(
    $api->getSuccessfulCollectionReport($date)
);
