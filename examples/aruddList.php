<?php

use PennyAppeal\SmartDebit\Api;

include 'bootstrap.php';

$args = $app->getIndemnityListArgs();
$api = new Api($app->getHost(), $app->getUser(), $app->getPass(), $app->getPslId(), $app->getAgent());
$app->dumpApiResponse(
    $api->aruddList($args['fromDate'], $args['tillDate'], $args['maxResults'], $args['startIndex'], $args['idFrom'])
);
