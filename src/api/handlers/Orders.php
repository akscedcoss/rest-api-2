<?php

namespace Api\Handlers;

use Phalcon\Di\Injectable;
use  MongoDB\BSON\ObjectID;
use Exception;

class Orders extends Injectable
{
    public function createOrder()
    {
        $response = [];
        $bodyData = $this->request->getJsonRawBody();
        // product_Id ,qty,address,email of person ,Status 
        // Check body is in Right Format 
        if (isset($bodyData->product_id) && isset($bodyData->qty) && isset($bodyData->address)) {
            //Use components to Get Email And name 
            $emailAndName = [];
            // Check if Product_id Is correct or not 
            try {
                $collection = $this->mongo->products;
                // if(strlen($bodyData->product_id) == "24" && ctype_xdigit($bodyData->product_id)){ }

                $exist = $collection->find(['_id' => new ObjectID($bodyData->product_id)]);
            } catch (Exception $e) {
                $response['status_code'] = 404;
                $response['msg'] = 'Product Id Not Found ';
                return json_encode($response);
            }




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

        if (isset($bodyData->order_id) && isset($bodyData->product_id) && isset($bodyData->status)) {
            // Check For Order status 
            if ($bodyData->status == 'Dispatched' || $bodyData->status == 'Delivered' || $bodyData->status == 'Pending' || $bodyData->status == 'In Transit') {
                // Check if Order Id is correct 
                try {
                    $collection = $this->mongo->orders;
                    // $exist = $collection->find(['_id' => new ObjectID($bodyData->order_id)]);
                    // Update Order Status 
                    $collection->updateOne(['_id' => new ObjectID($bodyData->order_id)],['$set'=>['status'=>$bodyData->status]]);
                    $response['status_code'] = 200;
                    $response['msg'] = 'Order Updated !!!!!';
                    return json_encode($response);
                } catch (Exception $e) {
                    $response['status_code'] = 404;
                    $response['msg'] = 'Order Id Not Found ';
                    return json_encode($response);
                }
            } else {
                // when Order Status Is Not in Format 
                $response['status_code'] = 404;
                $response['msg'] = ' Order Status Is Not in Format ... can be Dispatched,Delivered,Pending,In Transit';
                return json_encode($response);
            }
        } else {
            // when Data is Not In Format
            $response['status_code'] = 400;
            $response['msg'] = 'Data Not in Format';
            return json_encode($response);
        }
    }
}
