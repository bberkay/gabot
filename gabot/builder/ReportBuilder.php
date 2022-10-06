<?php
namespace Gabot\Builder;

use Google\Analytics\Data\V1beta\BatchRunReportsResponse;
use Google\Analytics\Data\V1beta\RunReportResponse;
use Google\Analytics\Data\V1beta\RunRealtimeReportResponse;

enum HEADERTYPE: string
{
    case DIMENSIONS = "dimensionHeaders";
    case METRICS = "metricHeaders";
}

trait ReportBuilder{

    /**
     * Set Query Title using Headers
     * @example country_city_active1DayUsers_active28DayUsers
     * @param array $report_headers
     * @return string
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
     * @param RunReportResponse|RunRealtimeReportResponse $report
     * @return array
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
     * @param RunReportResponse|RunRealtimeReportResponse $report
     * @param boolean $unnamed_list
     * @return array
     */
    private static function getRows(RunReportResponse|RunRealtimeReportResponse $report, bool $unnamed_list) : array
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
                if($unnamed_list == false)
                    $result[$query_title][$query_counter][$query_headers[$header_counter]] = $val->getValue();
                else
                    $result[$query_title][$query_counter][$header_counter] = $val->getValue();
                $header_counter++;
            }
            // Metrics
            foreach($row->getMetricValues() as $val){  
                if($unnamed_list == false)          
                    $result[$query_title][$query_counter][$query_headers[$header_counter]] = $val->getValue();
                else
                    $result[$query_title][$query_counter][$header_counter] = $val->getValue();
                $header_counter++;
            }
            $query_counter++;
        }

        return $result;
    }

    /**
     * Get Reports
     * @example ["country_city_active1DayUsers_active28DayUsers" => [{"country":"turkey", "city":"istanbul", "active1DayUsers":"1"}, {...}]]
     * @param BatchRunReportsResponse|RunRealtimeReportResponse $response
     * @param bool $unnamed_list {"country":"turkey", "city":"istanbul", "active1DayUsers":"1"} -> ["turkey", "istanbul", "1"]
     * @return array
     */
    public function getReports(bool $unnamed_list = false) : array
    {
        $result = [];
        if(get_class($this->response) === get_class(new BatchRunReportsResponse())){
            foreach($this->response->getReports() as $report){
                $result[] = self::getRows($report, $unnamed_list);
            }
        }
        else{
            $result = self::getRows($this->response, $unnamed_list);
        }
        return $result;
    }

    
}

?>