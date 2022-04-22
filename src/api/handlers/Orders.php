<?php

namespace Api\Handlers;

use Phalcon\Di\Injectable;

class Orders extends Injectable
{
    public function createOrder()
    {

        // print_r($this->request->get());
        $bodyData = $this->request->getJsonRawBody();
        // product_Id ,qty,address,email of person ,Status 

        if (isset($bodyData->product_id) && isset($bodyData->qty) && isset($bodyData->address)) {
            // tokenByUser Get Data From Token 
            //Fire event to check Setting 
            $eventsManager = $this->eventsManager;
            $data = $this->eventsManager->fire('notifications:getDataFromToken', $this, "amit");

            // Write Order in database 
            return json_encode($data);
        } else {

            return json_encode(['status_code' => '400', 'msg' => 'Data Not in Format', 'data' => $bodyData]);
        }
    }
}
