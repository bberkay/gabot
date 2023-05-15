<?php
namespace Gabot\Model;

class Query{
    private array $date_ranges;
    private array $dimensions;
    private array $metrics;
    private bool $keep_empty_row;
    
    /**
     * @param array $date_ranges       : ["start_date" => "28daysAgo", "end_date" => "today"]
     * @param array $dimensions        : ["eventName"]
     * @param array $metrics           : ["eventCount"]
     * @param boolean $keep_empty_row
     * @link non-realtime              : https://developers.google.com/analytics/devguides/reporting/data/v1/rest/v1beta/properties/batchRunReports
     * @link realtime                  : https://developers.google.com/analytics/devguides/reporting/data/v1/rest/v1beta/properties/runRealtimeReport
     */
    public function __construct(array $date_ranges = [], array $dimensions = [], array $metrics = [], bool $keep_empty_row = false){ 
        if($dimensions == [] && $metrics == [])
            throw new \Exception("Dimensions and Metrics cannot be empty at the same time.");
        $this->date_ranges = $date_ranges;
        $this->dimensions = $dimensions;
        $this->metrics = $metrics;
        $this->keep_empty_row = $keep_empty_row;
    }

    // GETTERS 
    public function getDateRanges():array { return $this->date_ranges; }
    public function getDimensions():array { return $this->dimensions; }
    public function getMetrics():array { return $this->metrics; }
    public function getKeepEmptyRow():bool { return $this->keep_empty_row; }
}

?>