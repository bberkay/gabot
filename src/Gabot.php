<?php
namespace Bberkay\Gabot;

use Bberkay\Gabot\Client;
use Bberkay\Gabot\Builder\ReportBuilder;
use Bberkay\Gabot\Builder\RequestBuilder;
use Bberkay\Gabot\Model\Query;

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
     * @link for more info about create request: https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en
     */
    public function runRequest(array $request): array
    {
        return $this->report_builder->getReports($this->client->batchRunReports($this->setRequest($request)));
    }

    /**
     * Run Realtime requests
     * @link for more info about create request: https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en
     */
    public function runRealtimeRequest(Query $request): array
    {
        return $this->report_builder->getReports($this->client->runRealtimeReport($this->setRealtimeRequest($request)));
    }

    /**
     * Get active users by device
     * @param string $activity_limit "activeUsers", "active1dayUsers", "active7dayUsers", "active28dayUsers"
     * @link for more info: https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en
     */
    public function getActiveUsersByDevice(string $start_date = "28daysAgo", string $end_date = "today", string $activity_limit = "activeUsers"): array
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
     * @param string $activity_limit "activeUsers", "active1dayUsers", "active7dayUsers", "active28dayUsers"
     * @link for more info: https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en
     */
    public function getActiveUsersByOS(string $start_date = "28daysAgo", string $end_date = "today", string $activity_limit = "activeUsers"): array
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
     * @param string $activity_limit "activeUsers", "active1dayUsers", "active7dayUsers", "active28dayUsers"
     * @link for more info: https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en
     */
    public function getActiveUsersByBrowser(string $start_date = "28daysAgo", string $end_date = "today", string $activity_limit = "activeUsers"): array
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
     * @param string $activity_limit "activeUsers", "active1dayUsers", "active7dayUsers", "active28dayUsers"
     * @link for more info: https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en
     */
    public function getActiveUsersByCity(string $start_date = "28daysAgo", string $end_date = "today", string $activity_limit = "activeUsers"): array
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
     * @param string $activity_limit "activeUsers", "active1dayUsers", "active7dayUsers", "active28dayUsers"
     * @link for more info: https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en
     */
    public function getActiveUsersByCountry(string $start_date = "28daysAgo", string $end_date = "today", string $activity_limit = "activeUsers"): array
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
     * @param string $activity_limit "activeUsers", "active1dayUsers", "active7dayUsers", "active28dayUsers"
     * @link for more info: https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema?hl=en
     */
    public function getActiveUsersByCountryAndCity(string $start_date = "28daysAgo", string $end_date = "today", string $activity_limit = "activeUsers"): array
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