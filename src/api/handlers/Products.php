<?php

namespace Api\Handlers;

use Phalcon\Di\Injectable;

class Products extends Injectable
{
    public function get($select = "", $where = "", $limit = 10, $page = 1)
    { 
       
        $products = array(
            array("select" => $select, "where" => $where, "limit" => $limit, "page" => $page),
            array("name" => "Product2", "price" => 40),
        );

        return json_encode($this->mongo);
    }
    public function search($keyword)
    {
        $keywords = explode(' ', urldecode($keyword));

        $outputArr = [];
        foreach ($keywords as $k => $v) {
            $collection = $this->mongo->products;
            $keyword = ['$regex' => $v];
            $cursor = $collection->find(['name' => $keyword])->toArray();
    
            $outputArr=array_merge($outputArr,$cursor);
        }


        return json_encode($outputArr);
    }

    public function getProducts($per_page = 10, $page = 1)
    {
       
  
        $options = [
            "limit" => intval($per_page),
            "skip" => intval($per_page) * (intval($page) -1)
        ];
        $response = $this->mongo->products->find(array(), $options)->toArray();
 
        return json_encode($response);
    }
}
