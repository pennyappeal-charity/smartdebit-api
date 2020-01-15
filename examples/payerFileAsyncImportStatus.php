<?php

use PennyAppeal\SmartDebit\Api;

include "bootstrap.php";

$args = $app->getPayerFileAsyncImportStatusArgs();
$api = new Api($app->getHost(), $app->getUser(), $app->getPass(), $app->getPslId(), $app->getAgent());
$app->dumpApiResponse(
    $api->payerFileAsyncImportStatus($args['id'])
);
