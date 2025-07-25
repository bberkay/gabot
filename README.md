<h1 align = "center">Simple Bot with PHP for Google Analytics 4</h1>

<h3>Table of Contents</h3>

1. [Introduction](#introduction)
2. [Installation](#installation)
3. [How to use?](#how-to-use)
4. [Report List](#ready-made-report-list)
5. [Source Code](#source-code-of-example)

## Introduction
Gabot allows users to easily retrieve their Google Analytics 4 data and use it in an integrated way with the chart.js library.  It also has the ability to create custom and/or real-time reports, along with about 11 pre-made reports, without limiting the capabilities of Google Analytics or chart.js.

## Installation
#### What do you need?
* [GA4 Property ID](https://support.google.com/analytics/answer/12270356?hl=en#:~:text=A%20Measurement%20ID%20is%20an,same%20as%20your%20destination%20ID.)
* [Chart.js Library](https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js)
* [credentials.json](https://developers.google.com/analytics/devguides/reporting/data/v1/quickstart-client-libraries#step_1_enable_the_api)(this json file has contains keys like `type`, `project_id`, `private_key_id`, `private_key` etc.).

Download with [composer](https://getcomposer.org/Composer-Setup.exe)
```
composer require gabot/gabot:dev-master@dev
```

# How to Use?
## Setup
```php
require __DIR__.'/vendor/autoload.php'; // Composer Autoload

use Gabot\Gabot;
use Gabot\Model\Query;
use Gabot\Model\Chart; // Optional, for visualize with chart.js

$property_id = "GA4 Property ID";
$credentials_path = "./credentials.json path";
$gabot = Gabot::getInstance($property_id, $credentials_path);
```
## Ready-made Reports
Gabot has some ready-made reports like getActiveUsersByOS as seen in the example, all ready-made reports are listed in [Ready Made Report List](https://github.com/bberkay/gabot/edit/master/README.md#ready-made-report-list) section.
### Get
```php
$reports = $gabot->getActiveUsersByOS("28daysAgo", "today");
print_r($reports->get());
```
```json
[
    {
       "operatingSystem_activeUsers":[
          {
            "operatingSystem":"iOS",
            "activeUsers":"2"
          },
          {
            "operatingSystem":"Windows",
            "activeUsers":"4"
          },
          {
            "operatingSystem":"Linux",
            "activeUsers":"1"
          }
       ]
    }
 ]
```
### Visualize
```html
<h4>myChart - operatingSystem_activeUsers</h4>
<canvas id="myChart" style="width:100%;max-width:700px"></canvas>

<!-- Chart.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
```
```php
// Default chart settings
$reports->visualize(new Chart(chart_id:"myChart"));

// With custom chart settings
$reports->visualize(new Chart(
    chart_id:"myChart", 
    chart_options:'{scales: { yAxes: [{ ticks: { beginAtZero: true } }] }, legend: {display:false}}', 
    chart_type:"bar"
));
```
![myChart](https://i.ibb.co/dGCwdzM/myChart.png)
<br/>
<br/>

## Custom Reports
You can also call your own custom reports with query object as seen in example. For more information about date_ranges, dimensions and metrics check the google analytics documentation from [here.](https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en)
### Get
```php
$reports = $gabot->runRequest([
    new Query(
        date_ranges:["start_date" => "28daysAgo", "end_date" => "today"],
        dimensions:["browser"],
        metrics:["activeUsers"]
    ),
    new Query(
        date_ranges:["start_date" => "28daysAgo", "end_date" => "today"],
        dimensions:["deviceCategory"],
        metrics:["activeUsers"]
    ), 
    new Query(
        date_ranges:["start_date" => "28daysAgo", "end_date" => "today"],
        dimensions:["operatingSystem"],
        metrics:["activeUsers"]
    )
]);
print_r($reports->get());
```
```json
[
    {
       "browser_activeUsers":[
          {
            "browser":"Chrome",
            "activeUsers":"6"
          },
          {
            "browser":"Edge",
            "activeUsers":"1"
          },
       ]
    },
    {
       "deviceCategory_activeUsers":[
          {
            "deviceCategory":"Desktop",
            "activeUsers":"5"
          },
          {
            "deviceCategory":"Mobile",
            "activeUsers":"2"
          },
       ]
    },
    {
       "operatingSystem_activeUsers":[
          {
            "browser":"Windows",
            "activeUsers":"4"
          },
          {
            "browser":"iOS",
            "activeUsers":"2"
          },
          {
            "browser":"Linux",
            "activeUsers":"1"
          },
       ]
    }
]
```
### Visualize
```html
<h4>myChart2 - browser_activeUsers</h4>
<canvas id="myChart2" style="width:100%;max-width:700px"></canvas>
<h4>myChart3 - deviceCategory_activeUsers</h4>
<canvas id="myChart3" style="width:100%;max-width:700px"></canvas>
<h4>myChart4 - operatingSystem_activeUsers</h4>
<canvas id="myChart4" style="width:100%;max-width:700px"></canvas>
```
```php
$reports->visualize([
    new Chart(
        chart_id:"myChart2", 
        chart_options:'{scales: { yAxes: [{ ticks: { beginAtZero: true } }] }, legend: {display:false}}', 
        chart_type:"bar"
    ),
    new Chart(
        chart_id:"myChart3", 
        chart_options:'{scales: { yAxes: [{ ticks: { beginAtZero: true } }] }, legend: {display:false}}', 
        chart_type:"bar"
    ),
    new Chart(
        chart_id:"myChart4", 
        chart_options:'{scales: { yAxes: [{ ticks: { beginAtZero: true } }] }, legend: {display:false}}', 
        chart_type:"bar"
    )
]);
```
![myChart2-myChart3-myChart4](https://i.ibb.co/y4SD6B2/my-Chart234.png)
<br/>
<br/>

## Realtime Reports
Apart from custom and ready-made reports, Gabot can also call real-time reports with query object and use them with chart.js as seen in example. For more information about realtime dimensions and metrics check the google analytics documentation from [here.](https://developers.google.com/analytics/devguides/reporting/data/v1/realtime-api-schema?hl=en)
### Get
```php
 print_r($gabot->runRealtimeRequest(
    new Query(
        dimensions:["country"],
        metrics:["activeUsers"]
    )
)->get()); // Real-time reports can be visualized like any other.
```
```json
[
    {
       "country_activeUsers":[
          {
            "country":"United States",
            "activeUsers":"4"
          },
          {
            "country":"Turkey",
            "activeUsers":"3"
          },
       ]
    }
]
```
<br/>

## Ready-made Report List
All reports that are available in Gabot currently, if you don't have the query you're looking for, you can learn how to add your own report to the bot from the [Add New Report](https://github.com/bberkay/gabot/edit/master/README.md#add-new-report-to-gabot) section.
| Description | Function |
| ------------- | ------------- |
| Get active users by device  | getActiveUsersByDevice() |
| Get active users by operating system  | getActiveUsersByOs() |
| Get active users by browser  | getActiveUsersByBrowser() |
| Get active users by city  | getActiveUsersByCity() |
| Get active users by country  | getActiveUsersByCountry() |
| Get active users by country and city | getActiveUsersByCountryAndCity() |
| Get active users by page path  | getActiveUsersByPagePath() |
| Get active users by language  | getActiveUsersByLanguage() |
| Get active users by first user source  | getActiveUsersByFirstUserSource() |
| Get active users by region  | getActiveUsersByRegion() |	
| Get active users by gender | getActiveUsersByGender() |

- For more information, please visit [Google Analytics Data API](https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en)
- Also you can use [GA4 Query Explorer](https://ga-dev-tools.google/ga4/query-explorer/)
<br/>

## Add New Report To Gabot
- You can add this template to the end of the `src/Gabot.php` file, then edit it however you want.
```php
/**
 * Function description
 * @param {type_of_param} param Parameter description
 * @returns {Report}
 */
public function newReport(string $start_date, string $end_date): Report
{
	return $this->runRequest([
            new Query(
                metrics: ["activeUsers"], // Metrics: https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en#metrics
                dimensions: ["city"], // Dimensions: https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en#dimensions
                date_ranges: ["start_date" => $start_date, "end_date" => $end_date] // Date Ranges: https://developers.google.com/analytics/devguides/reporting/data/v1/basics?hl=en#report_request
            )
        ]);
}
```
- Then you can use this `$gabot->newReport("28daysAgo", "today")->get();` way like any other ready-made report
<br/>

# Source Code of Example
```html
<html>
    <head>
        <title>Gabot Example File - Source Code</title>
    </head>
    <body>
	<h4>myChart - operatingSystem_activeUsers</h4>
        <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
	<div style="display:flex">
	<div>
	<h4>myChart2 - browser_activeUsers</h4>
        <canvas id="myChart2" style="width:100%;max-width:1000px"></canvas>
	</div>
	<div>
	<h4>myChart3 - deviceCategory_activeUsers</h4>
        <canvas id="myChart3" style="width:100%;max-width:1000px"></canvas>
	</div>
	<div>
	<h4>myChart4 - operatingSystem_activeUsers</h4>
        <canvas id="myChart4" style="width:100%;max-width:1000px"></canvas>
	</div>
	</div>
        
        <!-- Chart.js Library --->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
        <?php 
        // Setup
        require __DIR__.'/vendor/autoload.php'; // Composer Autoload

        use Gabot\Gabot;
        use Gabot\Model\Query;
        use Gabot\Model\Chart; // Optional, for visualize
    
        $property_id = "GA4-Property-ID";
        $credentials_path = "credentials.json";
        $gabot = Gabot::getInstance($property_id, $credentials_path);
        
        // Ready-made Reports
        $reports = $gabot->getActiveUsersByOS("28daysAgo", "today");
        print_r($reports->get());
           
        // Default chart settings
        $reports->visualize(new Chart(chart_id:"myChart"));

        // With custom chart settings
        /*$reports->visualize(new Chart(
            chart_id:"myChart", 
            chart_options:'{scales: { yAxes: [{ ticks: { beginAtZero: true } }] }, legend: {display:false}}', 
            chart_type:"bar"
        ));*/
        
        // Custom Reports
        $result = $gabot->runRequest([
            new Query(
                date_ranges:["start_date" => "28daysAgo", "end_date" => "today"],
                dimensions:["browser"],
                metrics:["activeUsers"]
            ),
            new Query(
                date_ranges:["start_date" => "28daysAgo", "end_date" => "today"],
                dimensions:["deviceCategory"],
                metrics:["activeUsers"]
            ), 
            new Query(
                date_ranges:["start_date" => "28daysAgo", "end_date" => "today"],
                dimensions:["operatingSystem"],
                metrics:["activeUsers"]
            )
        ]);
        
        $result->visualize([
            new Chart(
                chart_id:"myChart2", 
                chart_options:'{scales: { yAxes: [{ ticks: { beginAtZero: true } }] }, legend: {display:false}}', 
                chart_type:"bar"
            ),
            new Chart(
                chart_id:"myChart3", 
                chart_options:'{scales: { yAxes: [{ ticks: { beginAtZero: true } }] }, legend: {display:false}}', 
                chart_type:"bar"
            ),
            new Chart(
                chart_id:"myChart4", 
                chart_options:'{scales: { yAxes: [{ ticks: { beginAtZero: true } }] }, legend: {display:false}}', 
                chart_type:"bar"
            )
        ]);
        
        // Realtime Reports
        print_r($gabot->runRealtimeRequest(
           new Query(
                dimensions:["country"],
                metrics:["activeUsers"]
            )
        )->get()); // Real-time reports can be visualized like any other.
        ?>
    </body>
</html>
```

<hr>
<h5 align="center"><a href="mailto:berkaykayaforbusiness@gmail.com">berkaykayaforbusiness@gmail.com</a></h5> 
