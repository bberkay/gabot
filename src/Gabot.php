<?php
namespace Gabot;

use Gabot\Client;
use Gabot\Builder\ReportBuilder;
use Gabot\Builder\RequestBuilder;
use Gabot\Model\Query;
use Gabot\Model\Report;

class Gabot extends Client
{
    private static $instance = null;
    private $request_builder;
    private $report_builder;

    // Constructor
    private function __construct(string $property_id, string $credentials_path)
    {
        Client::__construct($property_id, $credentials_path);
        $this->request_builder = new RequestBuilder();
        $this->report_builder = new ReportBuilder();
    }

    /**
     * Singleton instance
     */
    public static function getInstance(string $property_id, string $credentials_path) : Gabot
    {
        if (self::$instance == null) {
            self::$instance = new Gabot($property_id, $credentials_path);
        }
        return self::$instance;
    }

    /**
     * Set Request
     */
    public function setRequest(array $query): array
    {
        if(is_array($query)){
            $this->realtime = false;
            return $this->request_builder->setRequest($query, $this->property);
        }
        else{
            throw new \Exception("Query must be an array");
        }
    }

    /**
     * Set Realtime Request
     */
    public function setRealtimeRequest(Query $query): array
    {
        if(is_array($query)){
            throw new \Exception("Query must be an object<Query>");
        }
        else{
            $this->realtime = true;
            return $this->request_builder->setRealtimeRequest($query, $this->property);
        }
    }

    /**
     * Run requests
     * @param Array<Query>
     * @link for more info about create request: https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en
     */
    public function runRequest(array $request): Report
    {
        return new Report($this->report_builder->getReports($this->client->batchRunReports($this->setRequest($request))));
    }

    /**
     * Run Realtime requests
     * @link for more info about create request: https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en
     */
    public function runRealtimeRequest(Query $request): Report
    {
        return new Report($this->report_builder->getReports($this->client->runRealtimeReport($this->setRealtimeRequest($request))));
    }

    /**
     * Get active users by device
     * @param {string} activity_limit the activity limit of the report, default(activeUsers), examples(activeUsers, active1DayUsers, active7DayUsers, active28DayUsers)
     */
    public function getActiveUsersByDevice(string $start_date, string $end_date, string $activity_limit = "activeUsers"): Report
    {
        return $this->runRequest([
            new Query(
                metrics: [$activity_limit],
                dimensions: ["deviceCategory"],
                date_ranges: ["start_date" => $start_date, "end_date" => $end_date]
            )
        ]);
    }

    /**
     * Get active users by os
     * @param {string} activity_limit the activity limit of the report, default(activeUsers), examples(activeUsers, active1DayUsers, active7DayUsers, active28DayUsers)
     */
    public function getActiveUsersByOS(string $start_date, string $end_date, string $activity_limit = "activeUsers"): Report
    {
        return $this->runRequest([
            new Query(
                metrics: [$activity_limit],
                dimensions: ["operatingSystem"],
                date_ranges: ["start_date" => $start_date, "end_date" => $end_date]
            )
        ]);
    }

    /**
     * Get active users by browser
     * @param {string} activity_limit the activity limit of the report, default(activeUsers), examples(activeUsers, active1DayUsers, active7DayUsers, active28DayUsers)
     */
    public function getActiveUsersByBrowser(string $start_date, string $end_date, string $activity_limit = "activeUsers"): Report
    {
        return $this->runRequest([
            new Query(
                metrics: [$activity_limit],
                dimensions: ["browser"],
                date_ranges: ["start_date" => $start_date, "end_date" => $end_date]
            )
        ]);
    }

    /**
     * Get active users by city
     * @param {string} activity_limit the activity limit of the report, default(activeUsers), examples(activeUsers, active1DayUsers, active7DayUsers, active28DayUsers)
     */
    public function getActiveUsersByCity(string $start_date, string $end_date, string $activity_limit = "activeUsers"): Report
    {
        return $this->runRequest([
            new Query(
                metrics: [$activity_limit],
                dimensions: ["city"],
                date_ranges: ["start_date" => $start_date, "end_date" => $end_date]
            )
        ]);
    }

    /**
     * Get active users by country
     * @param {string} activity_limit the activity limit of the report, default(activeUsers), examples(activeUsers, active1DayUsers, active7DayUsers, active28DayUsers)
     */
    public function getActiveUsersByCountry(string $start_date, string $end_date, string $activity_limit = "activeUsers"): Report
    {
        return $this->runRequest([
            new Query(
                metrics: [$activity_limit],
                dimensions: ["country"],
                date_ranges: ["start_date" => $start_date, "end_date" => $end_date]
            )
        ]);
    }

    /**
     * Get active users by country and city
     * @param {string} activity_limit the activity limit of the report, default(activeUsers), examples(activeUsers, active1DayUsers, active7DayUsers, active28DayUsers)
     */
    public function getActiveUsersByCountryAndCity(string $start_date, string $end_date, string $activity_limit = "activeUsers"): Report
    {
        return $this->runRequest([
            new Query(
                metrics: [$activity_limit],
                dimensions: ["country", "city"],
                date_ranges: ["start_date" => $start_date, "end_date" => $end_date]
            )
        ]);
    }
}