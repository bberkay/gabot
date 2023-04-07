<?php
namespace Gabot;
use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;

class Client{
    // Constructor
    public function __construct()
    {
        $this->client = null;
        $this->request = null;
        $this->response = null;
        $this->property = null;
        $this->realtime = false;
    }

    /**
     * @param string $property_id      : Google Analytics 4 Property ID
     * @param string $credentials_path : "credentials.json" Path
     * @return void
     */
    public function setAuth(string $property_id, string $credentials_path = null) : void
    {
        if($credentials_path === null)
            $credentials_path = __DIR__. '/credentials.json';
        putenv("GOOGLE_APPLICATION_CREDENTIALS=$credentials_path");
        $this->client = new BetaAnalyticsDataClient();
        $this->property = "properties/" . $property_id;
        $this->request["property"] = $this->property;
    }
}
?>