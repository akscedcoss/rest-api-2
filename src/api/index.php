<?php

use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\Application;
use Phalcon\Http\Request;
// for events 
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
require_once("vendor/autoload.php");
$loader = new Loader();

$loader->registerNamespaces(
    [
        'Api\Handlers' => './handlers',
        'App\Listener' => './Listener'
    ]
);

$loader->register();


$prod = new Api\Handlers\Products();
$token = new Api\Handlers\Token();
$order= new  Api\Handlers\Orders();

// Event  Managment Start 
global $eventsManager;
$eventsManager = new EventsManager();
$eventsManager->attach(
    'notifications',
    new App\Listener\NotificationsListener()
);


$container = new FactoryDefault();
$app = new Micro($container);
$application = new Application($container);
$container->set(
    'mongo',
    function () {
        $mongo =  new MongoDB\Client('mongodb://mongo', array('username' => 'root', "password" => '2password123456789'));
        return $mongo->store;
    },
    true
);
$app->before(
    function () use ($app) {
        $request = new Request();
        $tokenByUser= $request->get('token');
        if (is_null($tokenByUser)) {
            echo json_encode(['msg' => "Please Provide Token"]);
            die;
        } else {
            // Validate Token time Expiry using Components 
            
        }
    }
);
// NOT Found
$app->notFound(
    function () use ($app) {
        $message = 'Nothing to see here. Move along....';
        $app->response
            ->setStatusCode(404, 'Not Found')
            ->sendHeaders()
            ->setContent($message)
            ->send();
    }
);
// Products 
$app->get(
    '/invoices/view/{id}/{where}/{limit}/{page}',
    [$prod, 'get']
);

$app->get(
    '/products/search/{keyword}',
    [$prod, 'search']
);



$app->get(
    '/products/get',
    [$prod, 'getProducts']
);

$app->get(
    '/products/get/{per_page}/{page}',
    [$prod, 'getProducts']
);

// Token 
$app->get(
    '/getToken/{name}/{email}/{role}',
    [$token, 'getToken']
);

// Orders 
$app->post('/createOrder',
[$order, 'createOrder']);

$app->put('/updateOrderStatus',
[$order, 'updateOrderStatus']);



$_SERVER["REQUEST_URI"] = str_replace("/api", "", $_SERVER["REQUEST_URI"]);


$app->handle(


    $_SERVER["REQUEST_URI"]
);
