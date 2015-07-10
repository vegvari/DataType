<?php

namespace Data\Type\Exceptions;

use InvalidArgumentException;

class InvalidStringException extends InvalidArgumentException
{
    protected $message = 'Invalid string';
}
