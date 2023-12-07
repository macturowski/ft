<?php

namespace App\Exceptions;

use Exception;

class UserHasTooFewCards extends Exception
{
    public function __construct($message = "", $code = 0, $previous = null)
    {
        $this->message = 'User has to few cards.';
        $this->code = '422';
    }
}
