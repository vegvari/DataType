<?php

namespace Data\Type;

class DateTime extends \Carbon\Carbon
{
    protected $value;

    /**
     * @see BasicInterface
     */
    public function __construct($value = null, $timezone = null)
    {
    	if ($value !== null) {
    		$value = $this->check($value);
    	}

    	parent::__construct($value, $timezone);

    	if ($value !== null) {
    		$this->value = (string) $this;
    	}
    }

	/**
     * @see BasicInterface
     */
    public static function make($value = null, $timezone = null)
    {
        $class = get_called_class();
        return new $class($value, $timezone);
    }

    /**
     * @see BasicInterface
     */
    public static function cast($value)
    {
        if ($value === null) {
            throw new \InvalidArgumentException();
        }

        return self::make($value)->value;
    }

    public static function castNullable($value)
    {
        return self::make($value)->value;
    }

    /**
     * @see BasicInterface
     */
    public static function castSilent($value)
    {
        try {
            return self::cast($value);
        } catch (\InvalidArgumentException $e) {
        }
    }

    /**
     * @see BasicInterface
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * @see BasicInterface
     */
    public function set($value, \DateTimeZone $timezone = null)
    {
        if ($timezone === null) {
            $timezone = $this->getTimezone();
        }

        return $this->make($value, $timezone);
    }

    /**
     * @see BasicInterface
     */
    public function check($value)
    {
        if ($value instanceof DateTime) {
            return $value->value;
        }

        return $value;
    }

    public function setTimezone($value)
    {
        parent::setTimezone($timezone);
        if ($this->value !== null) {
            $this->value = (string) $this;
        }
    }
}
