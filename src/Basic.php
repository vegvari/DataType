<?php

namespace Data\Type;

abstract class Basic implements TypeInterface
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * Constructor
     *
     * @param mixed $value
     */
    protected function __construct($value = null)
    {
        if ($value !== null) {
            $this->value = $this->check($value);
        }
    }

    /**
     * Instance factory
     *
     * @param  mixed $value
     * @return Basic
     */
    public static function make($value = null)
    {
        $class = get_called_class();
        return new $class($value);
    }

    /**
     * Casting to the type, null not allowed
     *
     * @param  mixed $value
     * @return Basic
     */
    public static function cast($value)
    {
        if ($value === null) {
            throw new \InvalidArgumentException();
        }

        return self::make($value)->value();
    }

    /**
     * Casting to the type, null allowed
     *
     * @param  mixed $value
     * @return Basic
     */
    public static function castNullable($value)
    {
        return self::make($value)->value();
    }

    /**
     * Casting to the type, hide exception if any (return null)
     *
     * @param  mixed $value
     * @return Basic
     */
    public static function castSilent($value)
    {
        try {
            return self::cast($value);
        } catch (\InvalidArgumentException $e) {
        }
    }

    /**
     * @see TypeInterface
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @see TypeInterface
     */
    public function set($value)
    {
        if ($value !== null) {
            $value = $this->check($value);
        }

        $this->value = $value;

        return $this;
    }

    /**
     * @see TypeInterface
     */
    public function check($value)
    {
        if ($value instanceof Basic) {
            $value = $value->value;
        }

        return $value;
    }

    /**
     * @see TypeInterface
     */
    public function __toString()
    {
        if ($this->value === false) {
            return '0';
        }

        return (string) $this->value;
    }
}
