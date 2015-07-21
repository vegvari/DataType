<?php

namespace Data\Type\Exceptions;

class InvalidMonthException extends InvalidDateTimeException
{
    protected $message = 'Invalid month';
}
