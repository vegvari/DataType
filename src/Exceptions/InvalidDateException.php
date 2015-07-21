<?php

namespace Data\Type\Exceptions;

class InvalidDateException extends InvalidDateTimeException
{
    protected $message = 'Invalid date';
}
