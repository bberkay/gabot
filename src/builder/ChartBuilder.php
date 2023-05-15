<?php

namespace Gabot\Builder;

use Gabot\Model\Chart;

class ChartBuilder{

    /**
     * Create Chart For Report
     * @return string JavaScript Code
     */
    protected function createChart(array $report, Chart $chart): string
    {
        $report_tiles = explode("_", array_keys($report)[0]);
        if(count($report_tiles) == 2 && $report_tiles[1] == "activeUsers"){
            return "<script>".$this->createChartByActiveUsers($report, $chart)."</script>";
        }
    }

    /**
     * Create Chart For Report By Active Users
     * @return string JavaScript Code
     */
    private function createChartByActiveUsers(array $report, Chart $chart): string
    {
        $xvalues = [];
        $yvalues = [];
        foreach($report[array_keys($report)[0]] as $item){
            $item = array_values($item);
            array_push($xvalues, $item[0]);
            array_push($yvalues, $item[1]);
        }

        return $chart->setData($xvalues, $yvalues)->getChart();
    }
}

?>