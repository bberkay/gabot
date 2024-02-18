<?php
namespace Gabot\Builder;

use Gabot\Model\Query;
use Gabot\Builder\QueryBuilder;

class RequestBuilder{
    /**
     * Set Realtime Request
     */
    public function setRealtimeRequest(Query $query, string $property_id) : array
    {

        $request["property"] = $property_id;
        if(is_array($query))
            throw new \Exception("Realtime requests must be Query not array");
        //print_r(array_keys(QueryBuilder::createQuery($query)));
        return array_merge($request, QueryBuilder::createQuery($query));
    }

    /**
     * Set Non-realtime Request
     * @example [new Query(), new Query(), ...]
     */
    public function setRequest(array $query, string $property_id) : array
    {

        $request["property"] = $property_id;
        if(!is_array($query))
            throw new \Exception("Non-realtime requests must be array");
        if(count($query) > 5)
            throw new \Exception("Up to 5 queries can be run simultaneously.");
        foreach($query as $query_item){
            if($query_item->getDateRanges() == [])
                throw new \Exception("Non-realtime requests must contain date ranges");
            $request["requests"][] = QueryBuilder::createQuery($query_item);
        }

        return $request;
    }

}

?>
