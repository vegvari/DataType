<?php

namespace Data\Type;

abstract class Type implements TypeInterface
{
    protected $value;

    /**
     * @see TypeInterface
     */
    public function __construct($value)
    {
        $this->value = $this->check($value);
    }

    /**
     * @see TypeInterface
     */
    public static function create($value)
    {
        $class = get_called_class();
        return new $class($value);
    }

    /**
     * @see TypeInterface
     */
    public static function cast($value)
    {
        $value = self::create($value)->value();
        return $value;
    }

    /**
     * @see TypeInterface
     */
    public static function castNullable($value)
    {
        if ($value === null) {
            return;
        }

        return self::cast($value);
    }

    /**
     * @see TypeInterface
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
     * @see TypeInterface
     */
    public function check($value)
    {
        if ($value instanceof Type) {
            return $value->value();
        }

        return $value;
    }

    /**
     * @see TypeInterface
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Create new Bool instance using the value
     *
     * @return Bool
     */
    public function toBool()
    {
        return new Bool($this->value);
    }

    /**
     * Return bool value
     *
     * @return bool
     */
    public function boolValue()
    {
        return (new Bool($this->value))->value();
    }

    /**
     * Create new Float instance using the value
     *
     * @return bool
     */
    public function toFloat()
    {
        return new Float($this->value);
    }

    /**
     * Return float value
     *
     * @return float
     */
    public function floatValue()
    {
        return (new Float($this->value))->value();
    }

    /**
     * Create new Int instance using the value
     *
     * @return Int
     */
    public function toInt()
    {
        return new Int($this->value);
    }

    /**
     * Return int value
     *
     * @return int
     */
    public function intValue()
    {
        return (new Int($this->value))->value();
    }

    /**
     * Create new String instance using the value
     *
     * @return String
     */
    public function toString()
    {
        return new String($this->value);
    }

    /**
     * Return string value
     *
     * @return string
     */
    public function stringValue()
    {
        return (new String($this->value))->value();
    }

    /**
     * @see typeInterface::__toString
     */
    public function __toString()
    {
        return (string) $this->value;
    }
}
