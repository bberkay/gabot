<?php

require '../vendor/autoload.php'; // Composer Autoload
require '../vendor/gabot/autoload.php'; // Gabot Autoload

use Gabot\Gabot;
use Gabot\Model\Query;

// Gabot Realtime Example
$gabot = Gabot::getInstance("351228781", __DIR__."/credentials.json");
$data = $gabot->runRealtimeRequest(
   new Query(
        // realtime query doesn't get date ranges for more https://developers.google.com/analytics/devguides/reporting/data/v1/realtime-api-schema
        dimensions:["country", "city"], 
        metrics:["activeUsers"]
   )
);
$realtime_result = json_encode($data, true);
// Output Json
$output_file = fopen("realtime-output.json", "w");
fwrite($output_file, $realtime_result);
fclose($output_file);
?>