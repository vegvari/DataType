<?php

namespace Data\Type;

class DateTime extends \Carbon\Carbon
{
    protected $value;

    protected function __construct($value = null, $timezone = null)
    {
    	if ($value !== null) {
    		$value = $this->check($value);
    	}

    	parent::__construct($value, $timezone);

    	if ($value !== null) {
    		$this->value = (string) $this;
    	}
    }

    public static function make($value = null, $timezone = null)
    {
        $class = get_called_class();
        return new $class($value, $timezone);
    }

    public function value()
    {
        return $this->value;
    }

    public function set($value, \DateTimeZone $timezone = null)
    {
        if ($timezone === null) {
            $timezone = $this->getTimezone();
        }

        return $this->make($value, $timezone);
    }

    public function check($value)
    {
        if ($value instanceof \DateTime) {
            return $value->value;
        }

        return $value;
    }

    public function setTimezone($value)
    {
        parent::setTimezone($value);
        if ($this->value !== null) {
            $this->value = (string) $this;
        }
    }
}
