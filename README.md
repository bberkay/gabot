# Basic Bot with PHP for Google Analytics 4
1. [Installation](https://github.com/bberkay/gabot-php#installation)
2. [How to use?](https://github.com/bberkay/gabot-php#how-to-use)
## Installation
#### What you need?
* [credentials.json](https://developers.google.com/analytics/devguides/reporting/data/v1/quickstart-client-libraries#step_1_enable_the_api)(this json file has contains keys like `type`, `project_id`, `private_key_id`, `private_key` etc.).
* [GA4 Property ID](https://support.google.com/analytics/answer/12270356?hl=en#:~:text=A%20Measurement%20ID%20is%20an,same%20as%20your%20destination%20ID.)

Download with [composer](https://getcomposer.org/Composer-Setup.exe)
```
$ composer require gabot/gabot:dev-master@dev
```
## How to Use?
* Setup
```php
require __DIR__.'/vendor/autoload.php'; // Composer Autoload

use Gabot\Gabot;

$property_id = "GA4 Property ID";
$credentials_path = "./credentials.json path";
$gabot = Gabot::getInstance($property_id, $credentials_path);
```
```php
print_r($gabot->getActiveUsersByDevice());
print_r($gabot->getActiveUsersByOS());
```
```json
[
    {
       "deviceCategory":[
          {
             "desktop":"5",
             "mobile":"3",
          },
       ]
    },
    {
       "operatingSystem":[
          {
             "windows":"14",
             "android":"5"
          }
       ]
    }
 ]
```
* Custom Reports
```php
$gabot->getCustomReport(
   dimension:["browser"],
   metrics:["activeUsers"],
   start_date:"28daysAgo",
   end_date:"today"
);
```
```json
{
    "browser":[
       {
          "chrome":"5",
          "safari":"3",
       }
    ]
 }
```
```php
$gabot->getRealtimeCustomReport(
   dimensions:["city"],
   metrics:["activeUsers"]
);
```
```json
[
    {
    "city":[
       {
          "Istanbul":"5",
          "Paris":"3",
       }
    ]
 }
]
```



