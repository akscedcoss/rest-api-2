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

    //  Guzzle  Starts  Here 
    public function getJsonResponseUsingGuzzle($url)
    {
        $client = new Client();

        $response = $client->request('GET', $url);

        $response = json_decode($response->getBody()->getContents());
        return $response;
    }
}
