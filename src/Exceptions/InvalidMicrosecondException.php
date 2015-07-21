<?php

namespace Data\Type\Exceptions;

class InvalidMicrosecondException extends InvalidDateTimeException
{
    protected $message = 'Invalid microsecond';
}
