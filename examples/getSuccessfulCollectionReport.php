<?php

use PennyAppeal\SmartDebit\Api;

if ($argc == 2 && ($date = date_create($argv[1]))) {
    include "bootstrap.php";

    $api = new Api($host, $user, $pass, $pslId, $agent);

    dumpApiResponse(
        $api->getSuccessfulCollectionReport($date)
    );

} else {
    echo("Usage: \"php " . __FILE__ . " <date>\"\n");
}
