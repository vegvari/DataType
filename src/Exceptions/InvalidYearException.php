<?php

namespace Data\Type\Exceptions;

class InvalidYearException extends InvalidDateTimeException
{
    protected $message = 'Invalid year';
}
