<?php

namespace Api\Handlers;

use Phalcon\Di\Injectable;

class Orders extends Injectable
{
    public function createOrder()
    {  
        $response = [];
        $bodyData = $this->request->getJsonRawBody();
        // product_Id ,qty,address,email of person ,Status 
        // Check body is in Right Format 
        if (isset($bodyData->product_id) && isset($bodyData->qty) && isset($bodyData->address)) {
            //Fire event to Get Name And Email From  Token
            $emailAndName = $this->eventsManager->fire('notifications:getDataFromToken', $this, $this->request->get('token'));
            // Check if Product_id Is correct or not 


            // Create order 
            $collection = $this->mongo->orders;
            $insertOneResult = $collection->insertOne([
                'name' =>  $emailAndName['name'],
                'email' => $emailAndName['email'],
                'product_id' => $bodyData->product_id,
                'qty' => $bodyData->qty,
                'address' => $bodyData->address,
                'status' => "Pending"
            ]);

            // Return Response 
            $response['status_code'] = 200;
            $response['msg'] = 'Order Created successfully';
            $response['order-id'] = $insertOneResult->getInsertedId();
            return json_encode($response);

        } else {
            $response['status_code'] = 400;
            $response['msg'] = 'Data Not in Format';
            $response['data'] = $bodyData;
            return json_encode($response);
        }
    }


    public function updateOrderStatus()
    {
        // Check body is in Right Format 
        $response = [];
        $bodyData = $this->request->getJsonRawBody();
        print_r($bodyData);
        echo "Dfgdfgdfgdf";
        die;
        // Check If Order_id Exists
        //  Check if Status is in Specific Format 
    }
}
