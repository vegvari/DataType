<?php

namespace Data\Type\Exceptions;

class InvalidDayException extends InvalidDateTimeException
{
    protected $message = 'Invalid day';
}
