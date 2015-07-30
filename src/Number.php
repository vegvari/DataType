<?php

namespace Data\Type;

use Exception;

abstract class Number extends Type
{
    /**
     * Negation
     *
     * @return FloatType
     */
    public function neg()
    {
        if ($this->value === null) {
            return new FloatType();
        }

        return new FloatType(-$this->value);
    }

    /**
     * Addition
     *
     * @param  mixed     $value
     * @return FloatType
     */
    public function add($value)
    {
        $value = Cast::Float($value);

        if ($this->value === null) {
            return new FloatType();
        }

        return new FloatType($this->value + $value);
    }

    /**
     * Subtraction
     *
     * @param  mixed     $value
     * @return FloatType
     */
    public function sub($value)
    {
        $value = Cast::Float($value);

        if ($this->value === null) {
            return new FloatType();
        }

        return new FloatType($this->value - $value);
    }

    /**
     * Multiplication
     *
     * @param  mixed     $value
     * @return FloatType
     */
    public function mul($value)
    {
        $value = Cast::Float($value);

        if ($this->value === null) {
            return new FloatType();
        }

        return new FloatType($this->value * $value);
    }

    /**
     * Division
     *
     * @param  mixed     $value
     * @return FloatType
     */
    public function div($value)
    {
        $value = Cast::Float($value);

        if ($value === null || $value === 0.0) {
            throw new Exception('Division by zero');
        }

        if ($this->value === null) {
            return new FloatType();
        }

        return new FloatType($this->value / $value);
    }

    /**
     * Modulus
     *
     * @param  mixed     $value
     * @return FloatType
     */
    public function mod($value)
    {
        $value = Cast::Float($value);

        if ($this->value === null) {
            return new FloatType();
        }

        return new FloatType($this->value % $value);
    }

    /**
     * Exponentiation
     *
     * @param  mixed     $value
     * @return FloatType
     */
    public function exp($value)
    {
        $value = Cast::Float($value);

        if ($this->value === null) {
            return new FloatType();
        }

        return new FloatType(pow($this->value, $value));
    }

    /**
     * Alias of exp
     * @see exp
     */
    public function pow($value)
    {
        return $this->exp($value);
    }

    /**
     * Square root
     *
     * @return FloatType
     */
    public function sqrt()
    {
        if ($this->value === null) {
            return new FloatType();
        }

        return new FloatType(sqrt($this->value));
    }

    /**
     * Nth root. Product is always FloatType.
     *
     * @return FloatType
     */
    public function root($value)
    {
        $value = Cast::Float($value);

        if ($this->value === null) {
            return new FloatType();
        }

        return new FloatType(pow($this->value, 1 / $value));
    }

    /**
     * Equal
     *
     * @param  mixed $value
     * @return bool
     */
    public function eq($value)
    {
        $instance = new static($value);

        if ($this->value() === $instance->value()) {
            return true;
        }

        return false;
    }

    /**
     * Not equal
     *
     * @param  mixed $value
     * @return bool
     */
    public function ne($value)
    {
        $instance = new static($value);

        if ($this->value() !== $instance->value()) {
            return true;
        }

        return false;
    }

    /**
     * Greater than
     *
     * @param  mixed $value
     * @return bool
     */
    public function gt($value)
    {
        $instance = new static($value);

        if ($this->value() > $instance->value()) {
            return true;
        }

        return false;
    }

    /**
     * Greater than or equal
     *
     * @param  mixed $value
     * @return bool
     */
    public function gte($value)
    {
        $instance = new static($value);

        if ($instance->isNull() && $this->isNull()) {
            return true;
        } elseif (! $instance->isNull() && ! $this->isNull()) {
            if ($this->value() >= $instance->value()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Less than
     *
     * @param  mixed $value
     * @return bool
     */
    public function lt($value)
    {
        $instance = new static($value);

        if ($this->value() < $instance->value()) {
            return true;
        }

        return false;
    }

    /**
     * Less than or equal
     *
     * @param  mixed $value
     * @return bool
     */
    public function lte($value)
    {
        $instance = new static($value);

        if ($instance->isNull() && $this->isNull()) {
            return true;
        } elseif (! $instance->isNull() && ! $this->isNull()) {
            if ($this->value() <= $instance->value()) {
                return true;
            }
        }

        return false;
    }
}
