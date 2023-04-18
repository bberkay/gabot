<?php
namespace Bberkay\Gabot\Builder;

use Google\Analytics\Data\V1beta\BatchRunReportsResponse;
use Google\Analytics\Data\V1beta\RunReportResponse;
use Google\Analytics\Data\V1beta\RunRealtimeReportResponse;

class ReportBuilder{

    /**
     * Set Query Title using Headers
     * @example country_city_active1DayUsers_active28DayUsers
     */
    private static function setQueryTitle(array $report_headers) : string
    {
        $title = "";
        foreach($report_headers as $header){
            if($title == "")
                $title .= $header;   
            else
                $title .= "_".$header;
        }
        return $title;
    }

    /**
     * Get Dimensions and Metrics Headers
     * @example ["country", "city", "active1DayUsers", ...]
     */
    private static function getQueryHeaders(RunReportResponse|RunRealtimeReportResponse $report) : array
    {
        $headers = [];
        foreach($report->getDimensionHeaders() as $header){
            array_push($headers, $header->getName());
        }
        foreach($report->getMetricHeaders() as $header){
            array_push($headers, $header->getName());
        }
        return $headers;
    }

    /**
     * Get Dimensions and Metrics Values
     * @example ["country" => "Turkey", "city" => "Istanbul", "active1DayUsers" => "1"]
     */
    private static function getRows(RunReportResponse|RunRealtimeReportResponse $report) : array
    {
        $result = [];
        $query_counter = 0;
        // Query Title and Headers
        $query_headers = self::getQueryHeaders($report);
        $query_title = self::setQueryTitle($query_headers);
        foreach($report->getRows() as $row){
            $header_counter = 0;
            // Dimensions
            foreach($row->getDimensionValues() as $val){
                $result[$query_title][$query_counter][$query_headers[$header_counter]] = $val->getValue();
                $header_counter++;
            }
            // Metrics
            foreach($row->getMetricValues() as $val){       
                $result[$query_title][$query_counter][$query_headers[$header_counter]] = $val->getValue();
                $header_counter++;
            }
            $query_counter++;
        }

        return $result;
    }

    /**
     * Get Reports
     * @params ({RunRealtimeReportResponse|BatchRunReportsResponse}) $response
     * @example ["country_city_active1DayUsers_active28DayUsers" => [{"country":"turkey", "city":"istanbul", "active1DayUsers":"1"}, {...}]]
     */
    public function getReports(BatchRunReportsResponse|RunRealtimeReportResponse $response) : array
    {
        $result = [];
        if(get_class($response) === get_class(new BatchRunReportsResponse())){
            foreach($response->getReports() as $report){
                $result[] = self::getRows($report);
            }
        }
        else{
            $result = self::getRows($response);
        }
        return $result;
    }

    
}

?>