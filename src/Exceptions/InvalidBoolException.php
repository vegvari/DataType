<?php

namespace Data\Type\Exceptions;

use InvalidArgumentException;

class InvalidBoolException extends InvalidArgumentException
{
    protected $message = 'Invalid bool';
}
