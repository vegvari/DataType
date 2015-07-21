<?php

namespace Data\Type\Exceptions;

class InvalidHourException extends InvalidDateTimeException
{
    protected $message = 'Invalid hour';
}
