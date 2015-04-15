<?php

namespace Data\Type;

use Data\Type\Traits\SplSubject;

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
    public function __construct($value = null)
    {
        $this->set($value);
    }

    /**
     * Casting to the type, null not allowed
     *
     * @param  mixed $value
     * @return this
     */
    public static function cast($value)
    {
        $instance = new static($value);

        if ($instance->value() === null) {
            throw new \InvalidArgumentException('Not nullable');
        }

        return $instance->value();
    }

    /**
     * Casting to the type, null allowed
     *
     * @param  mixed $value
     * @return this
     */
    public static function castNullable($value)
    {
        $instance = new static($value);
        return $instance->value();
    }

    /**
     * Casting to the type, hide exception if any (return null)
     *
     * @param  mixed $value
     * @return this
     */
    public static function castSilent($value)
    {
        try {
            $instance = new static($value);
            return $instance->value();
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
    protected function check($value)
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
