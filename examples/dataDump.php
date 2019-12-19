<?php

use PennyAppeal\SmartDebit\Api;

include "bootstrap.php";

$args = $app->getDataDumpArgs();
$api = new Api($app->getHost(), $app->getUser(), $app->getPass(), $app->getPslId(), $app->getAgent());
$app->dumpApiResponse(
    $api->dataDump($args['payerReference'], $args['format'])
);
