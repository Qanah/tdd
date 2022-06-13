<?php

namespace App\Exceptions;

use Exception;

class NegativeNumberException extends Exception
{
    protected $message = "Negative Number not Allowed";
}
