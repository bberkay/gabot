<?php
namespace Bberkay\Gabot;
use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;

class Client{
    // Constructor
    public function __construct(string $property_id, string $credentials_path)
    {
        $this->client = null;
        $this->property = null;
        $this->setAuth($property_id, $credentials_path);
    }

    /**
     * Set authentication for Google Analytics 4
     */
    public function setAuth(string $property_id, string $credentials_path) : void
    {
        putenv("GOOGLE_APPLICATION_CREDENTIALS=$credentials_path");
        $this->property = "properties/" . $property_id;
        $this->client = new BetaAnalyticsDataClient();
    }
}
?>