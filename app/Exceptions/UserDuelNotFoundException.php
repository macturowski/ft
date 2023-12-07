<?php

namespace App\Exceptions;

use Exception;

class UserDuelNotFoundException extends Exception
{
    public function __construct($message = "", $code = 0, $previous = null)
    {
        $this->message = 'Duel not found';
        $this->code = '404';
    }
}
