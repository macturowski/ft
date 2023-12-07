<?php

namespace App\Exceptions;

use Exception;

class UserHasActiveDuel extends Exception
{
    public function __construct($message = "", $code = 0, $previous = null)
    {
        $this->message = 'User has active duel.';
        $this->code = '422';
    }
}
