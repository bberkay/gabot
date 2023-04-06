<?php
namespace Gabot\Builder;

use Gabot\Model\Query;
use Gabot\Builder\QueryBuilder;

trait RequestBuilder{

    /**
     * Clean Request
     * @return void
     */
    public function cleanRequest() : void
    {
        $this->request = [];
        $this->request["property"] = $this->property;
    }

    /**
     * @example [new Query(), new Query(), ...]
     * @param array $queries : Queries for request
     * @return void
     */
    public function setRequest(array|Query $query) : void
    {

        if($this->realtime){
            if(is_array($query))
                throw new \Exception("Realtime requests must be Query not array");
            $this->request = array_merge($this->request, QueryBuilder::createQuery($query));
        }   
        else{
            if(!is_array($query))
                throw new \Exception("Non-realtime requests must be array");
            if(count($query) > 5)
                throw new \Exception("Up to 5 queries can be run simultaneously.");
            foreach($query as $query_item){
                if($query_item->getDateRanges() == [])
                    throw new \Exception("Non-realtime requests must contain date ranges");
                $this->request["requests"][] = QueryBuilder::createQuery($query_item);
            }
        }
    }

}

?>