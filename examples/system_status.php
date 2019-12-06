<?php

include "bootstrap.php";

use PennyAppeal\SmartDebit\Api;

$api = new Api($host, $user, $pass, $pslId, $agent);

dumpApiResponse(
    $api->systemStatus()
);
