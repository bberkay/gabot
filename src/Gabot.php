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
     */
    public function runRequest(array $request): array
    {
        return $this->report_builder->getReports($this->client->batchRunReports($this->setRequest($request)));
    }

     /**
     * Run Realtime requests
     */
    public function runRealtimeRequest(Query $request): array
    {
        return $this->report_builder->getReports($this->client->runRealtimeReport($this->setRealtimeRequest($request)));
    }

}