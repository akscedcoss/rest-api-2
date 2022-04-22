<?php

namespace Api\Handlers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Phalcon\Di\Injectable;

class Token extends Injectable
{

    public function getToken($name,$email,$role)
    {
        $key = "example_key";
        $payload = array(
            "name" => $name,
            "email" => $email,
            "role" => $role,
            "nbf" => 1357000000
        );
        $jwt = JWT::encode($payload, $key, 'HS256');
        
       
        return json_encode(['payload'=>  $payload,'token'=>$jwt]);
    }

    public function tokenNotFound()
    {
                
        return json_encode(['msg'=>  "Token Not Found"]);
    }
}
