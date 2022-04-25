<?php

use Phalcon\Mvc\Controller;
use GuzzleHttp\Client;

define("BASE_API_URL", 'http://192.168.2.59:8080');

/**
 * IndexController class 
 * It has Function 
 */
class IndexController extends Controller
{
    //  Guzzle  Starts  Here 
    public function getJsonResponseUsingGuzzle($url)
    {
        $client = new Client();

        $response = $client->request('GET', $url);

        $response = json_decode($response->getBody()->getContents());
        return $response;
    }

    public function indexAction()
    {

        if ($this->request->isPost()) {
            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');
            $role = $this->request->getPost('role');
            $url = BASE_API_URL . "/api/getToken/$name/$email/$role";
            $this->view->token = $this->getJsonResponseUsingGuzzle($url)->token;
        }
    }

    public function aksAction()
    {
        echo "hi";

        $url = "http://192.168.2.30:8080/api/order/create?bearer=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6ImFrYXNoIiwiZW1haWwiOiJha2FzaEBnbWFpbC5jb20iLCJpZCI6eyIkb2lkIjoiNjI2Mjg2NmY5N2JjMWRhZDgxMDNhOTY0In19.mi1ijuCYveiAmE8R1eXP6hX3aVyeCOxyXA7d-XuZkII";
        $client = new Client();
        for ($i = 0; $i < 500000; $i++) {
            $response = $client->request('POST', $url, [
                'form_params' => [
                    'product id' => '626122ddde64f076a9e4977b',
                    'quantity' => $i. ' subham'
                ]
            ]);
            $response = json_decode($response->getBody()->getContents());
            print_r($response);
        }


        die;
    }
}
