<?php

namespace Data\Type\Exceptions;

use InvalidArgumentException;

class InvalidTimeZoneException extends InvalidArgumentException
{
    protected $message = 'Invalid timezone';
}
