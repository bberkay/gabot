<?php
require dirname(__DIR__).'/gabot/autoload.php';

use Gabot\Gabot;
use Gabot\Model\Query;

// Gabot Non-realtime Example
$gabot = Gabot::getInstance();
$gabot->setAuth("332338551");
$gabot->addRequest([ // Non-realtime request get array<Query>
    new Query(
        // non-realtime query must gate date ranges for more https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema
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
// Output Json
$output_file = fopen("nonrealtime-output.json", "w");
fwrite($output_file, $nonrealtime_result);
fclose($output_file);


// Gabot Realtime Example
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
// Output Json
$output_file = fopen("realtime-output.json", "w");
fwrite($output_file, $realtime_result);
fclose($output_file);


// getReports with unnamed_list=true
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
// Output Json
$output_file = fopen("unnamed-list-output.json", "w");
fwrite($output_file, $unnamed_list_result);
fclose($output_file);
?>