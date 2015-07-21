<?php

namespace Data\Type\Exceptions;

class InvalidMinuteException extends InvalidDateTimeException
{
    protected $message = 'Invalid minute';
}
