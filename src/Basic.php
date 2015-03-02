<?php

namespace Data\Type;

abstract class Basic
{
    protected $value;

    /**
     * Constructor
     *
     * @param mixed
     */
    public function __construct($value = null)
    {
        if ($value !== null) {
            $this->value = $this->check($value);
        }
    }

    /**
     * Create a new instance
     *
     * @param  mixed $value
     * @return Type
     */
    public static function create($value = null)
    {
        $class = get_called_class();
        return new $class($value);
    }

    /**
     * Create a new instance and return the value
     *
     * @param  mixed $value
     * @return mixed
     */
    public static function cast($value)
    {
        if ($value === null) {
            throw new \InvalidArgumentException();
        }

        return self::create($value)->value;
    }

    public static function castNullable($value)
    {
        return self::create($value)->value;
    }

    /**
     * Null if the value invalid
     *
     * @param  mixed $value
     * @return mixed
     */
    public static function castSilent($value)
    {
        try {
            return self::cast($value);
        } catch (\InvalidArgumentException $e) {
        }
    }

    /**
     * Return the value
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * Create a new instance
     *
     * @return mixed
     */
    public function set($value)
    {
        return $this->create($value);
    }

    /**
     * Check the value
     *
     * @param  mixed $value
     * @return mixed
     */
    public function check($value)
    {
        if ($value instanceof Basic) {
            return $value->value;
        }

        return $value;
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
