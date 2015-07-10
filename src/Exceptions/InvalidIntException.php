<?php

namespace Data\Type\Exceptions;

use InvalidArgumentException;

class InvalidIntException extends InvalidArgumentException
{
    protected $message = 'Invalid int';
}
