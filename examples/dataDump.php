<?php

use PennyAppeal\SmartDebit\Api;

include "bootstrap.php";

function usage()
{
    global $formats;
    $allowedFormats = implode('|', $formats);
    throw new Exception("\nUsage: php " . __FILE__ . " [-r <payer_reference>] [-f {$allowedFormats}]\n");
}

$payerReference = null;
$formats = ['XML', 'CSV'];
$format = $formats[0];

$argIndex = 1;
while ($argIndex < $argc) {
    switch ($argv[$argIndex]) {
        case '-r':
            $argIndex++;
            $payerReference = $argv[$argIndex];
            $argIndex++;
            break;
        case '-f':
            $argIndex++;
            $format = $argv[$argIndex];
            if (!in_array($format, $formats)) {
                usage();
            }
            $argIndex++;
            break;
        default:
            usage();
    }
}

$api = new Api($host, $user, $pass, $pslId, $agent);
dumpApiResponse(
    $api->dataDump($payerReference, $format)
);
