<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    public function __construct($message = "", $code = 0, $previous = null)
    {
        $this->message = 'User not found';
        $this->code = '404';
    }
}
