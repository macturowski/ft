<?php

namespace App\Exceptions;

use Exception;

class UserCardLimitException extends Exception
{
    public function __construct($message = "", $code = 0, $previous = null)
    {
        $this->message = 'User can not pick a new card';
        $this->code = '422';
    }
}
