<?php

use PennyAppeal\SmartDebit\Api;

include "bootstrap.php";

$args = $app->getDdiVariableUpdateArgs();
$api = new Api($app->getHost(), $app->getUser(), $app->getPass(), $app->getPslId(), $app->getAgent());
$app->dumpApiResponse(
    $api->ddiVariableUpdate(
        $args['ref'],
        [
            'default_amount' => $args['amount'],
        ]
    )
);
