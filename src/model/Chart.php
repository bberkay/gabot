<?php
namespace Gabot\Model;

class Chart{
    private string $chart_id;
    private string $chart_type;
    private string $chart_data;
    private string $chart_options;
    private string $chart;
    
    /**
     * @param string $chart_id      : "myChart"
     * @param string $chart_type    : "bar", "line", "scatter" etc.
     * @param string $chart_data    : "{labels: ["Italy", "France", "Spain", "USA", "Argentina"], datasets: [{backgroundColor: ["red", "green","blue","orange","brown"], data: [55, 49, 44, 24, 15]}]}"
     * @param string $chart_options : "{scales: { yAxes: [{ ticks: { beginAtZero: true } }] }}"
     * @link                        : https://www.chartjs.org/docs/latest/configuration/
     */
    public function __construct(string $chart_id, string $chart_type = "", string $chart_data = "", string $chart_options = ""){ 
        $this->setDefaultChart();
        $this->setChartID($chart_id);
        if($chart_type != "") $this->setChartType($chart_type);
        if($chart_data != "") $this->setChartData($chart_data);
        if($chart_options != "") $this->setChartOptions($chart_options);
    }

    /**
     * Get Chart
     */
    public function getChart(): string 
    { 
        return $this->chart; 
    }


    /**
     * Set Chart ID
     * @param string $chart_id : "myChart"
     */
    private function setChartID(string $chart_id): Chart
    {
        $this->chart = str_replace('new Chart("myChart"', 'new Chart("'.$chart_id.'"', $this->chart);
        return $this;
    }

    /**
     * Set Chart Type
     * @param string $chart_type : "bar", "line", "scatter" etc.
     * @link                     : https://www.chartjs.org/docs/latest/charts/
     */
    private function setChartType(string $chart_type): Chart
    {
        $this->chart = str_replace('type: "bar"', 'type: "'.$chart_type.'"', $this->chart);
        return $this;
    }

    /**
     * Set Chart Data
     * @param string $chart_data : "{labels: ["Italy", "France", "Spain", "USA", "Argentina"], datasets: [{backgroundColor: ["red", "green","blue","orange","brown"], data: [55, 49, 44, 24, 15]}]}"
     * @link                     : https://www.chartjs.org/docs/latest/charts/
     */
    private function setChartData(string $chart_data): Chart
    {
        $this->chart = str_replace('data: {
                                            labels: ["Italy", "France", "Spain", "USA", "Argentina"],
                                            datasets: [{
                                                backgroundColor: ["red", "green","blue","orange","brown"],
                                                data: [55, 49, 44, 24, 15]
                                            }]
                                        }', "data: ".$chart_data, $this->chart);
        return $this;
    }

    /**
     * Set Chart Options
     * @param string $chart_options : "{scales: { yAxes: [{ ticks: { beginAtZero: true } }] }}"
     * @link                        : https://www.chartjs.org/docs/latest/configuration/
     */
    private function setChartOptions(string $chart_options): Chart
    {
        $chart_options = preg_replace('/^{(.*)}$/', '$1', $chart_options);
        $this->chart = str_replace("legend: { display: false },", $chart_options, $this->chart);
        return $this;                                        
    }

    /**
     * Set Chart Data
     * @param array $xvalues : ["Italy", "France", "Spain", "USA", "Argentina"]
     * @param array $yvalues : [55, 49, 44, 24, 15]
     */
    public function setData(array $xvalues, array $yvalues): Chart
    {
        // Set X values
        $this->chart = str_replace('labels: ["Italy", "France", "Spain", "USA", "Argentina"]', "labels: ".$this->arrayToString($xvalues), $this->chart);

        // Set Y values
        $this->chart = str_replace('data: [55, 49, 44, 24, 15]', "data: ".$this->arrayToString(array_values($yvalues)), $this->chart);

        return $this;
    }

    /**
     * Set Default Chart
     */
    private function setDefaultChart(): void
    {
        $this->chart = '
            new Chart("myChart", {
                type: "bar",
                data: {
                    labels: ["Italy", "France", "Spain", "USA", "Argentina"],
                    datasets: [{
                        backgroundColor: ["red", "green","blue","orange","brown"],
                        data: [55, 49, 44, 24, 15]
                    }]
                },
                options: {
                    legend: { display: false },
                }
            });
        ';
    }

    /**
     * Convert Array to String for JavaScript
     * @param array $array : ["Italy", "France", "Spain", "USA", "Argentina"]
     */
    private function arrayToString(array $array): string
    {
        return "['".implode("','", $array)."']";
    }
}

?>