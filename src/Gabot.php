<?php
namespace Gabot;

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/autoload.php';

use Gabot\Client;
use Gabot\Builder\ReportBuilder;
use Gabot\Builder\RequestBuilder;

class Gabot extends Client
{
    use ReportBuilder;
    use RequestBuilder;
    private static $instance = null;

    // Constructor
    private function __construct()
    {
        Client::__construct();
    }

    /**
     * @return Gabot
     */
    public static function getInstance() : Gabot
    {
        // Singleton instance
        if (self::$instance == null) {
            self::$instance = new Gabot();
        }
        return self::$instance;
    }

    /**
     * Set Gabot to realtime analytics
     * @return void
     */
    public function setRealtime() : void
    {
        $this->realtime = true;
    }


    /**
     * Unset Gabot to realtime analytics
     * @return void
     */
    public function unSetRealtime() : void
    {
        $this->realtime = false;
    }


    /**
     * Google Analytics 4 Response
     * @return this
     */
    public function makeApiCall()                     
    {
        if($this->realtime)
            $this->response = $this->client->runRealtimeReport($this->request);          
        else
            $this->response = $this->client->batchRunReports($this->request);   
        return $this;
    }


}

