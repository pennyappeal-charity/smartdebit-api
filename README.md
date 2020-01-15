# smartdebit-api
PHP library for calling a subset of the SmartDebit API

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
directory and update it with your details.

Before running the ```ddiVariable*``` examples copy the ```ddiVariableValidate.json.template```
file and update any copies with your own content.

## Implemented API calls
* ```/api/data/dump```
* ```/api/addac/<addacId>```
* ```/api/addac/list```
* ```/api/arudd/<arrudId>```
* ```/api/arudd/list```
* ```/api/auddis/<auddisId>```
* ```/api/auddis/list```
* ```/api/ddi/variable/validate```
* ```/api/ddi/variable/create``` (not including multiple initial collections)
* ```/api/get_successful_collection_report```
* ```/api/indemnity/<indemnityId>```
* ```/api/indemnity/list```
* ```/api/system_status```
* ```/api/payer_file/async_import```
* ```/api/payer_file/<payer file import id>```

## Additional Calls
* ```setDebug(true | false | stream handle);``` Output connection debugging information to either
stdout or a stream