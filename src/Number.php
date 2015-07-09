<?php

namespace Data\Type;

use InvalidArgumentException;

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

        return new FloatType($this->value * -1);
    }

    /**
     * Addition
     *
     * @param  mixed     $value
     * @return FloatType
     */
    public function add($value)
    {
        if ($this->value === null) {
            return new FloatType();
        }

        return new FloatType($this->value + Cast::Float($value));
    }

    /**
     * Subtraction
     *
     * @param  mixed     $value
     * @return FloatType
     */
    public function sub($value)
    {
        if ($this->value === null) {
            return new FloatType();
        }

        return new FloatType($this->value - Cast::Float($value));
    }

    /**
     * Multiplication
     *
     * @param  mixed     $value
     * @return FloatType
     */
    public function mul($value)
    {
        if ($this->value === null) {
            return new FloatType();
        }

        return new FloatType($this->value * Cast::Float($value));
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

        if ($value === 0.0) {
            throw new InvalidArgumentException('Division by zero');
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

        if ($value === 0.0) {
            throw new InvalidArgumentException('Division by zero');
        }

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
        if ($this->value === null) {
            return new FloatType();
        }

        return new FloatType(pow($this->value, Cast::Float($value)));
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
        if ($this->value === null) {
            return new FloatType();
        }

        return new FloatType(pow($this->value, 1 / Cast::Float($value)));
    }

    /**
     * Equal
     *
     * @param  mixed $value
     * @return bool
     */
    public function eq($value)
    {
        $value = Cast::_Float($value);

        if ($this->value === $value) {
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
        $value = Cast::_Float($value);

        if ($this->value !== $value) {
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
        $value = Cast::Float($value);

        if ($this->value !== null && $this->value > $value) {
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
        $value = Cast::Float($value);

        if ($this->value !== null && $this->value >= $value) {
            return true;
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
        $value = Cast::Float($value);

        if ($this->value !== null && $this->value < $value) {
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
        $value = Cast::Float($value);

        if ($this->value !== null && $this->value <= $value) {
            return true;
        }

        return false;
    }
}
