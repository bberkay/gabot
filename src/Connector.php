<?php

require '../autoload.php'; // vendor autoload path(please change it to your path)

use Gabot\Gabot;

class GabotConnector{
    private $gabot;


    public function __construct(string $property_id, string $credentials_path){
        $this->gabot = Gabot::getInstance($property_id, $credentials_path);
    }

    /**
     * Get analytics data with Gabot
     */
    public function getAnalytics(string $action, string $start_date, string $end_date, string $activity_limit): JSON
    {
        // Filter action
        switch($action){
            case "getActiveUsersByDevice":
                return $this->gabot->getActiveUsersByDevice($start_date, $end_date, $activity_limit);
            case "getActiveUsersByOS":
                return $this->gabot->getActiveUsersByOS($start_date, $end_date, $activity_limit);
            case "getActiveUsersByBrowser":
                return $this->gabot->getActiveUsersByBrowser($start_date, $end_date, $activity_limit);
            case "getActiveUsersByCity":
                return $this->gabot->getActiveUsersByCity($start_date, $end_date, $activity_limit);
            case "getActiveUsersByCountry":
                return $this->gabot->getActiveUsersByCountry($start_date, $end_date, $activity_limit);
            case "getActiveUsersByCountryAndCity":
                return $this->gabot->getActiveUsersByCountryAndCity($start_date, $end_date, $activity_limit);
            default:
                return json_encode(["error" => "Invalid action"]);
        }
    }
}

$connector = new GabotConnector($_POST['property_id'], $_POST['credentials_path']);

// Get analytics data and print it for fetch request
print_r($connector->getAnalytics($_POST['data']['action'], $_POST['data']['start_date'], $_POST['data']['end_date'], $_POST['data']['activity_limit']));

?>