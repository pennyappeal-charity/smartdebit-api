# smartdebit-api
PHP library for calling the SmartDebit API

## Installation
```bash
composer require pennyappeal-charity/smartdebit-api
```
## Usage
```php
<?php

use GuzzleHttp\Exception\ConnectException;
use PennyAppeal\SmartDebit\Api;

include 'vendor/autoload.php';

$api = new Api(
    '<https://smartdebit.api.domain>',
    '<username>',
    '<password>',
    '<pslId>',
    '<userAgent>'
);

$date = new DateTime('2019-11-15');

try {
    $response = $api->getSuccessfulCollectionReport($date);

    echo("Response status code = {$response->getStatusCode()}\n");
    echo("Response body =\n{$response->getBody()->getContents()}\n");
} catch (ConnectException $exc) {
    echo("ConnectException: {$exc->getMessage()}\n");
}
```