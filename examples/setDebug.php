<?php

include "bootstrap.php";

use PennyAppeal\SmartDebit\Api;

$api = new Api($host, $user, $pass, $pslId, $agent);

$api->setDebug(true);
dumpApiResponse(
    $api->systemStatus()
);
