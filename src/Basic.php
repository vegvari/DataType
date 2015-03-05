<?php

namespace Data\Type;

abstract class Basic implements BasicInterface
{
    protected $value;

    /**
     * @see BasicInterface
     */
    public function __construct($value = null)
    {
        if ($value !== null) {
            $this->value = $this->check($value);
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

    /**
     * @see BasicInterface
     */
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
    public function value()
    {
        return $this->value;
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
        if ($value instanceof Basic) {
            return $value->value;
        }

        return $value;
    }

    /**
     * @see BasicInterface
     */
    public function __toString()
    {
        if ($this->value === false) {
            return '0';
        }

        return (string) $this->value;
    }
}
