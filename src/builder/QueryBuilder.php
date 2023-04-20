<?php

namespace Gabot\Builder;

use Gabot\Model\Query;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\RunReportRequest;


class QueryBuilder
{

    /**
     * Convert Array Items to GA4 Object
     * @example ["metrics" => ["activeUsers"]] -> ["metrics" => [new Metric(["name" => "activeUsers"])]]             
     */
    private static function toGA4Object(string $type, array $query_var): array
    {
        $converted_to_ga4 = [];
        if ($type === "date_ranges") {
            array_push($converted_to_ga4, new DateRange(["start_date" => $query_var["start_date"], "end_date" => $query_var["end_date"]]));
        } else if ($type === "dimensions" && $query_var !== []) {
            foreach ($query_var as $v) {
                array_push($converted_to_ga4, new Dimension(["name" => $v]));
            }
        } else if ($type === "metrics" && $query_var !== []) {
            foreach ($query_var as $v) {
                array_push($converted_to_ga4, new Metric(["name" => $v]));
            }
        }
        return $converted_to_ga4;
    }


    /**
     * Create Query
     * @example [Query] -> [new RunReportRequest(Query)]
     */
    public static function createQuery(Query $query): RunReportRequest|array
    {
        $request = null;
        if ($query->getDateRanges() == []) {
            $request = [
                "dimensions" => self::toGA4Object("dimensions", $query->getDimensions()),
                "metrics" => self::toGA4Object("metrics", $query->getMetrics()),
            ];
        } else {
            $request = new RunReportRequest([
                "date_ranges" => self::toGA4Object("date_ranges", $query->getDateRanges()),
                "dimensions" => self::toGA4Object("dimensions", $query->getDimensions()),
                "metrics" => self::toGA4Object("metrics", $query->getMetrics()),
                "keep_empty_rows" => $query->getKeepEmptyRow(),
            ]);
        }
        return $request;
    }
}