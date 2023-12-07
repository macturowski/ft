<?php

namespace App\Exceptions;

use Exception;

class UserCardUsedBeforeException extends Exception
{
    public function __construct($message = "", $code = 0, $previous = null)
    {
        $this->message = 'User card used before';
        $this->code = '422';
    }
}
