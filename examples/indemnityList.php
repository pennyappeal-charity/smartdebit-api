<?php

use PennyAppeal\SmartDebit\Api;

include "bootstrap.php";

function usage()
{
    throw new Exception("\nUsage: php " . __FILE__ . " [--max_results <max>] [--start_index <index>] "
        . "[--id_from <id>] [--from_date <from> --till_date <till>]\n");
}

$maxResults = null;
$startIndex = null;
$idFrom = null;
$fromDate = null;
$tillDate = null;

$argIndex = 1;
while ($argIndex < $argc) {
    $option = $argv[$argIndex];
    $argIndex++;
    switch ($option) {
        case '--max_results':
            $maxResults = $argv[$argIndex];
            $argIndex++;
            break;
        case '--start_index':
            $startIndex = $argv[$argIndex];
            $argIndex++;
            break;
        case '--id_from':
            $idFrom = $argv[$argIndex];
            $argIndex++;
            break;
        case '--from_date':
            $fromDate = new DateTime($argv[$argIndex]);
            $argIndex++;
            break;
        case '--till_date':
            $tillDate = new DateTime($argv[$argIndex]);
            $argIndex++;
            break;
        default:
            usage();
    }
}

$api = new Api($host, $user, $pass, $pslId, $agent);
dumpApiResponse(
    $api->indemnityList($fromDate, $tillDate, $maxResults, $startIndex, $idFrom)
);
