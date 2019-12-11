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

$date = new DateTime();

try {
    $response = $api->getSuccessfulCollectionReport($date);

    echo("Response status code = {$response->getStatusCode()}\n"
        . "Response body =\n{$response->getBody()->getContents()}\n");
} catch (ConnectException $exc) {
    echo("ConnectException: {$exc->getMessage()}\n");
}
```
## Examples
Before running any of the example scripts a ```.env``` file will need creating which contains your
SmartDebit API credentials. Copy the ```env.example``` file to ```.env``` in the ```examples```
directory and update it with your smartDebit API credentials.