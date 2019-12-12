<?php

use PennyAppeal\SmartDebit\Api;
use PennyAppeal\SmartDebitExample\JsonFileLoader;

include "bootstrap.php";

$loader = new JsonFileLoader();
$api = new Api($host, $user, $pass, $pslId, $agent);
dumpApiResponse(
    $api->ddiVariableCreate($loader->getDecodedJson())
);
