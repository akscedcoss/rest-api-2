<?php

namespace App\Listener;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Phalcon\Events\Event;
use Phalcon\Di\Injectable;
use Permissions;

class NotificationsListener extends Injectable
{
  public function getDataFromToken(Event $event, $component, $token)
  {
    $emailAndName = ['name' => 'Aman', 'email' => 'alberte@gmail.com'];
    return $emailAndName;
  }
  public function isTokenExpired(Event $event, $component, $token)
  {
    return false;
  }

}
