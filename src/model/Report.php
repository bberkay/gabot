<?php
namespace Gabot\Model;

use Gabot\Builder\ChartBuilder;
use Gabot\Model\Chart;

class Report extends ChartBuilder{
    
    private array $reports;

    public function __construct(array $reports)
    {
        $this->reports = $reports;
    }

    /**
     * Get reports
     */
    public function get(): array
    {
        return $this->reports;
    }

    /**
     * Prints HTML or JavaScript Code(Chart.js) for visualization
     * @params (Chart|Array<Chart>) $chart Chart object or array of Chart objects
     * 
     * @return string JavaScript Code like this:
     * <script>
     * var xValues = ["Italy", "France", "Spain", "USA", "Argentina"];
     * var yValues = [55, 49, 44, 24, 15];
     * var barColors = ["red", "green","blue","orange","brown"];
     * 
     * new Chart("myChart", {
     *    type: "bar",
     *    data: {
     *      labels: xValues,
     *      datasets: [{
     *        backgroundColor: barColors,
     *        data: yValues
     *      }]
     *    },
     *    options: {
     *      legend: {display: false},
     *      title: {
     *        display: true,
     *      }
     *    }
     * });
     * </script>
     */
    public function visualize(Chart|Array $chart): void
    {
        if(is_array($chart)) {
            $this->visualizeMultipleCharts($chart);
        }
        else{
            $this->visualizeSingleChart($chart);
        } 
    }

    /**
     * Visualize single chart
     */
    private function visualizeSingleChart(Chart $chart): void
    {
        $visualize_report = "";
        foreach($this->reports as $report){
            $visualize_report .= $this->createChart($report, $chart);
        }
        echo $visualize_report;
    }

    /**
     * Visualize multiple charts
     * @params Array<Chart> $charts
     */
    private function visualizeMultipleCharts(array $charts): void
    {
        $visualize_report = "";
        $count = count($this->reports);
        for($i = 0; $i < $count; $i++){
            $visualize_report .= $this->createChart($this->reports[$i], $charts[$i]);
        }
        echo $visualize_report;
    }
}

?>