<?php

namespace Data\Type\Exceptions;

use InvalidArgumentException;

class InvalidFloatException extends InvalidArgumentException
{
    protected $message = 'Invalid float';
}
