<?php

require '../vendor/autoload.php'; // Composer Autoload
require '../vendor/gabot/autoload.php'; // Gabot Autoload

use Gabot\Gabot;
use Gabot\Model\Query;

// Gabot Non-realtime Example
$gabot = Gabot::getInstance("351228781", __DIR__."\credentials.json");
$data = $gabot->runRequest([ // Non-realtime request get array<Query>
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

$nonrealtime_result = json_encode($data, true);
// Output Json
$output_file = fopen("nonrealtime-output.json", "w");
fwrite($output_file, $nonrealtime_result);
fclose($output_file);
?>