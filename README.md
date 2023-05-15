# Basic Bot with PHP for Google Analytics 4
1. [Installation](https://github.com/bberkay/gabot-php#installation)
2. [How to use?](https://github.com/bberkay/gabot-php#how-to-use)
## Installation
#### What you need?
* [GA4 Property ID](https://support.google.com/analytics/answer/12270356?hl=en#:~:text=A%20Measurement%20ID%20is%20an,same%20as%20your%20destination%20ID.)
* [Chart.js Library](https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js)
* [credentials.json](https://developers.google.com/analytics/devguides/reporting/data/v1/quickstart-client-libraries#step_1_enable_the_api)(this json file has contains keys like `type`, `project_id`, `private_key_id`, `private_key` etc.).

Download with [composer](https://getcomposer.org/Composer-Setup.exe)
```
composer require gabot/gabot:dev-master@dev
```
## How to Use?
* Setup
```php
require __DIR__.'/vendor/autoload.php'; // Composer Autoload

use Gabot\Gabot;
use Gabot\Model\Query;
use Gabot\Model\Chart; // Optional, for visualize with chart.js

$property_id = "GA4 Property ID";
$credentials_path = "./credentials.json path";
$gabot = Gabot::getInstance($property_id, $credentials_path);
```
* Ready-made Reports
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
            "activeUsers":"4"
          },
          {
            "operatingSystem":"Windows",
            "activeUsers":"3"
          },
       ]
    }
 ]
```
* Visualize
```html
<canvas id="myChart" style="width:100%;max-width:700px"></canvas>

<!-- Chart.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
```
```php
$reports->visualize(new Chart(
    chart_id:"myChart", 
    chart_options:'{scales: { yAxes: [{ ticks: { beginAtZero: true } }] }, legend: {display:false}}', 
    chart_type:"bar"
));
```
* Custom Reports
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
            "activeUsers":"4"
          },
          {
            "browser":"Safari",
            "activeUsers":"3"
          },
       ]
    }
]
```
* Visualize
```html
<canvas id="myChart2" style="width:100%;max-width:700px"></canvas>
<canvas id="myChart3" style="width:100%;max-width:700px"></canvas>
<canvas id="myChart4" style="width:100%;max-width:700px"></canvas>
```
```php
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
```
* Realtime Reports
```php
print_r($gabot->runRealtimeRequest(
   new Query(
        dimensions:["browser"],
        metrics:["activeUsers"]
    )
)->get()); // Real-time reports can be visualized like any other.
```
```json
[
    {
       "browser_activeUsers":[
          {
            "browser":"Chrome",
            "activeUsers":"4"
          },
          {
            "browser":"Safari",
            "activeUsers":"3"
          },
       ]
    }
]
```
### Source Code
```html
<html>
    <head>
        <title>Gabot Example File - Source Code</title>
    </head>
    <body>
        <canvas id="myChart" style="width:100%;max-width:700px"></canvas>
        <canvas id="myChart2" style="width:100%;max-width:700px"></canvas>
        <canvas id="myChart3" style="width:100%;max-width:700px"></canvas>
        <canvas id="myChart4" style="width:100%;max-width:700px"></canvas>
        
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
           
        $reports->visualize(new Chart(
            chart_id:"myChart", 
            chart_options:'{scales: { yAxes: [{ ticks: { beginAtZero: true } }] }, legend: {display:false}}', 
            chart_type:"bar"
        ));
        
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
                dimensions:["browser"],
                metrics:["activeUsers"]
            )
        )->get()); // Real-time reports can be visualized like any other.
        ?>
    </body>
</html>
```
