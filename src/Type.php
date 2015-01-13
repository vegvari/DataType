<?php

namespace Data\Type;

abstract class Type
{
    protected $value;

    /**
     * Constructor
     *
     * @param Mixed
     */
    public function __construct($value)
    {
        $this->value = $this->check($value);
    }

    /**
     * Create a new instance
     *
     * @param  Mixed $value
     * @return Type
     */
    public static function create($value)
    {
        $class = get_called_class();
        return new $class($value);
    }

    /**
     * Create a new instance and return the value itself
     *
     * @param  Mixed $value
     * @return Mixed
     */
    public static function cast($value)
    {
        $value = self::create($value)->value();
        return $value;
    }

    /**
     * Accept null
     *
     * @param  Mixed $value
     * @return Mixed
     */
    public static function castNullable($value)
    {
        if ($value === null) {
            return;
        }

        return self::cast($value);
    }

    /**
     * Return null if the value invalid
     *
     * @param  Mixed $value
     * @return Mixed
     */
    public static function castSilent($value)
    {
        try {
            return self::cast($value);
        } catch (\InvalidArgumentException $e) {
            $value = null;
        }
    }

    /**
     * Check the value
     *
     * @param  Mixed $value
     * @return Mixed
     */
    public function check($value)
    {
        if ($value instanceof Type) {
            return $value->value();
        }

        return $value;
    }

    /**
     * Return the value
     *
     * @return Mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Cast instance to string
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->value === false) {
            return '0';
        }

        return (string) $this->value;
    }
}
