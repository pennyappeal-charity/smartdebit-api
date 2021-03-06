<?php

use PennyAppeal\SmartDebit\Api;

include 'bootstrap.php';

$args = $app->getBacsListArgs();
$api = new Api($app->getHost(), $app->getUser(), $app->getPass(), $app->getPslId(), $app->getAgent());
$app->dumpApiResponse(
    $api->addacList($args['fromDate'], $args['tillDate'], $args['maxResults'], $args['startIndex'], $args['idFrom'])
);
