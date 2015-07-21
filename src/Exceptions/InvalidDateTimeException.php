<?php

namespace Data\Type\Exceptions;

use InvalidArgumentException;

class InvalidDateTimeException extends InvalidArgumentException
{
    protected $message = 'Invalid datetime';
}
