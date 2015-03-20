<?php

namespace Data\Type;

abstract class Basic implements \SplSubject
{
    use SplSubject;

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
        $this->set($value);
    }

    /**
     * Instance factory
     *
     * @param  mixed $value
     * @return Basic
     */
    public static function create($value = null)
    {
        return new static($value);
    }

    /**
     * Casting to the type, null not allowed
     *
     * @param  mixed $value
     * @return Basic
     */
    public static function cast($value)
    {
        $value = static::create($value)->value();

        if ($value === null) {
            throw new \InvalidArgumentException('Value is not nullable');
        }

        return $value;
    }

    /**
     * Casting to the type, null allowed
     *
     * @param  mixed $value
     * @return Basic
     */
    public static function castNullable($value)
    {
        return static::create($value)->value();
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
            return static::cast($value);
        } catch (\InvalidArgumentException $e) {
        }
    }

    /**
     * Set the value
     *
     * @param  mixed $value
     * @return this
     */
    public function set($value)
    {
        if ($value !== null) {
            $value = $this->check($value);
        }

        if ($value !== $this->value) {
            $this->value = $value;
            $this->notify();
        }

        return $this;
    }

    /**
     * Get the value
     *
     * @return mixed
     */
    public function value()
    {
        return $this->value;
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
            $value = $value->value;
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
