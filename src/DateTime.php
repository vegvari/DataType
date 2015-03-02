<?php

namespace Data\Type;

class DateTime extends \Carbon\Carbon implements BasicInterface
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
    		$this->value = $this->format('c');
    	}
    }

	/**
     * @see BasicInterface
     */
    public static function make($value = null)
    {
        $class = get_called_class();
        return new $class($value);
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
    public function set($value)
    {
        return $this->make($value);
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

	/**
     * @see BasicInterface
     */
    public function __toString()
    {
        return (string) $this->value;
    }
}
