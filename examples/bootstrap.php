<?php

use Dotenv\Dotenv;
use PennyAppeal\SmartDebitExample\App;

include '../vendor/autoload.php';

$dotEnv = Dotenv::createImmutable(__DIR__);
$dotEnv->load();
$dotEnv->required(['SD_HOST', 'SD_USER', 'SD_PASS', 'SD_PSLID', 'SD_AGENT']);

$app = new App($argc, $argv);
