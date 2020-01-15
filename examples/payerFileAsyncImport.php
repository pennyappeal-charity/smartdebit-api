<?php

use PennyAppeal\SmartDebit\Api;

include "bootstrap.php";

$args = $app->getPayerFileAsyncImportArgs();
$api = new Api($app->getHost(), $app->getUser(), $app->getPass(), $app->getPslId(), $app->getAgent());
$app->dumpApiResponse(
    $api->payerFileAsyncImport(basename($args['filename']), file_get_contents($args['filename']), $args['action'])
);
