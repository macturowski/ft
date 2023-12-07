<?php

namespace App\Exceptions;

use Exception;

class UserCardNotFoundException extends Exception
{
    public function __construct($message = "", $code = 0, $previous = null)
    {
        $this->message = 'User card not found';
        $this->code = '404';
    }
}
