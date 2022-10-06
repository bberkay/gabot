# Basic Bot with PHP for Google Analytics 4
1. [Installation](https://github.com/bberkay/gabot-v1#installation)
2. [How to use?](https://github.com/bberkay/gabot-v1#how-to-use)
## Installation
#### What you need?
* [Composer](https://getcomposer.org/Composer-Setup.exe)
* [Google Analytics 4 Account and "credentials.json"](https://developers.google.com/analytics/devguides/reporting/data/v1/quickstart-client-libraries#step_1_enable_the_api)

After downloading composer run this command with cmd in `'gabot'` folder
```
$ composer require google/analytics-data
```
then you have to add `'credentials.json'` file to same `'gabot'` folder, it should look like [this](https://drive.google.com/file/d/1NkY0svMSIVNp2HvixyJbHd-tZjiCr0XV/view?usp=sharing)
## How to Use?
* Setup
```php
require dirname(__DIR__).'/gabot/autoload.php';

use Gabot\Gabot;
use Gabot\Model\Query;

$gabot = Gabot::getInstance();
$gabot->setAuth("GA4-PROPERTY-ID");
```
* Non-realtime Example
```php
$gabot->addRequest([ // Non-realtime request get array<Query>
    new Query(
        // non-realtime query must take date ranges for more https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema
        date_ranges:["start_date" => "28daysAgo", "end_date" => "today"],
        dimensions:["country", "city"], 
        metrics:["active1DayUsers", "active28DayUsers"]
    ),
    new Query(
        date_ranges:["start_date" => "28daysAgo", "end_date" => "today"],
        metrics:["activeUsers"]
    ),
    new Query(
        date_ranges:["start_date" => "28daysAgo", "end_date" => "today"],
        metrics:["activeUsers", "active28DayUsers"]
    ),
    new Query(
        date_ranges:["start_date" => "28daysAgo", "end_date" => "today"],
        dimensions:["hostName"]
    )
]);
$nonrealtime_result = json_encode($gabot->makeApiCall()->getReports(), true);
```
```json
[
    {
       "country_city_active1DayUsers_active28DayUsers":[
          {
             "country":"Turkey",
             "city":"Istanbul",
             "active1DayUsers":"211",
             "active28DayUsers":"1240"
          },
          {
             "country":"United States",
             "city":"New York",
             "active1DayUsers":"12",
             "active28DayUsers":"92"
          }
       ]
    },
    {
       "activeUsers":[
          {
             "activeUsers":"14"
          }
       ]
    },
    {
       "activeUsers_active28DayUsers":[
          {
             "activeUsers":"223",
             "active28DayUsers":"1352"
          }
       ]
    },
    {
       "hostName":[
          {
             "hostName":"site.com"
          },
          {
             "hostName":"www.site.com"
          }
       ]
    }
 ]
```
* Realtime Example
```php
$gabot->setRealtime(); 
$gabot->cleanRequest(); // Realtime request and non-realtime request is different from each other so you must clean before set requests
$gabot->addRequest( // Realtime request get object[Query]
   new Query(
        // realtime query doesn't get date ranges for more https://developers.google.com/analytics/devguides/reporting/data/v1/realtime-api-schema
        dimensions:["country", "city"], 
        metrics:["activeUsers"]
   )
);
$realtime_result = json_encode($gabot->makeApiCall()->getReports(), true);
```
```json
{
    "country_city_activeUsers":[
       {
          "country":"Turkey",
          "city":"Istanbul",
          "activeUsers":"5"
       }
    ]
 }
```
* `getReports()` function with `unnamed_list=true`
```php
$gabot->unsetRealtime(); 
$gabot->cleanRequest();
$gabot->addRequest([ // Non-realtime request get array<Query>
    new Query(
        date_ranges:["start_date" => "28daysAgo", "end_date" => "today"],
        dimensions:["country", "city"], 
        metrics:["active1DayUsers", "active28DayUsers"]
    )
]);
$unnamed_list_result = json_encode($gabot->makeApiCall()->getReports(unnamed_list:true), true);
```
```json
[
    {
        "country_city_active1DayUsers_active28DayUsers":[
            [
                "Turkey",
                "Istanbul",
                "2",
                "2"
            ],
            [
                "United States",
                "(not set)",
                "1",
                "1"
            ]
        ]
    }
]
```



