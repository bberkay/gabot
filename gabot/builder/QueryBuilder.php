<?php

namespace Gabot\Builder;
use Gabot\Model\Query;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\RunReportRequest;

enum GA4TYPE
{
    case DATE_RANGES;
    case DIMENSIONS;
    case METRICS;
}

class QueryBuilder
{

    /**
     * @example                : ["metrics" => ["activeUsers"]] -> ["metrics" => [new Metric(["name" => "activeUsers"])]] 
     * @param GA4TYPE $type    : for query to ga4 object(daterange, dimension, metric) 
     * @param array $query_var : Query(Object) variables(date_ranges, dimenson, metrics)
     * @return array                  
     */
    private static function toGA4Object(GA4TYPE $type, array $query_var): array
    {
        $converted_to_ga4 = [];
        if ($type === GA4TYPE::DATE_RANGES) {
            array_push($converted_to_ga4, new DateRange(["start_date" => $query_var["start_date"], "end_date" => $query_var["end_date"]]));
        } else if ($type === GA4TYPE::DIMENSIONS && $query_var !== []) {
            foreach ($query_var as $v) {
                array_push($converted_to_ga4, new Dimension(["name" => $v]));
            }
        } else if ($type === GA4TYPE::METRICS && $query_var !== []) {
            foreach ($query_var as $v) {
                array_push($converted_to_ga4, new Metric(["name" => $v]));
            }
        }
        return $converted_to_ga4;
    }


    /**
     * @example            : [Query] -> [new RunReportRequest(Query)]
     * @param Query $query : Query for request
     * @return array|RunReportRequest
     */
    public static function createQuery(Query $query): RunReportRequest|array
    {
        $request = null;
        if ($query->getDateRanges() == []) {
            $request = [
                "dimensions" => self::toGA4Object(GA4TYPE::DIMENSIONS, $query->getDimensions()),
                "metrics" => self::toGA4Object(GA4TYPE::METRICS, $query->getMetrics()),
            ];
        } else {
            $request = new RunReportRequest([
                "date_ranges" => self::toGA4Object(GA4TYPE::DATE_RANGES, $query->getDateRanges()),
                "dimensions" => self::toGA4Object(GA4TYPE::DIMENSIONS, $query->getDimensions()),
                "metrics" => self::toGA4Object(GA4TYPE::METRICS, $query->getMetrics()),
                "keep_empty_rows" => $query->getKeepEmptyRow(),
            ]);
        }
        return $request;
    }
}
