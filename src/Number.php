<?php

namespace Data\Type;

abstract class Number extends Basic
{
    /**
     * Negation. Product is always FloatType.
     *
     * @return FloatType
     */
    public function neg()
    {
        if ($this->value === null) {
            return new FloatType($this->value);
        }

        return new FloatType($this->value * -1);
    }

    /**
     * Addition. Product is always FloatType.
     *
     * @param  mixed $value
     * @return FloatType
     */
    public function add($value)
    {
        $value = Cast::Float($value);

        if ($this->value === null) {
            return new FloatType($this->value);
        }

        return new FloatType($this->value + $value);
    }

    /**
     * Subtraction. Product is always FloatType.
     *
     * @param  mixed $value
     * @return FloatType
     */
    public function sub($value)
    {
        $value = Cast::Float($value);

        if ($this->value === null) {
            return new FloatType($this->value);
        }

        return new FloatType($this->value - $value);
    }

    /**
     * Multiplication. Product is always FloatType.
     *
     * @param  mixed $value
     * @return FloatType
     */
    public function mul($value)
    {
        $value = Cast::Float($value);

        if ($this->value === null) {
            return new FloatType($this->value);
        }

        return new FloatType($this->value * $value);
    }

    /**
     * Division. Product is always FloatType.
     *
     * @param  mixed $value
     * @return FloatType
     */
    public function div($value)
    {
        $value = Cast::Float($value);

        if ($value == 0) {
            throw new \InvalidArgumentException('Division by zero');
        }

        if ($this->value === null) {
            return new FloatType($this->value);
        }

        return new FloatType($this->value / $value);
    }

    /**
     * Modulus. Product is always FloatType.
     *
     * @param  mixed $value
     * @return FloatType
     */
    public function mod($value)
    {
        $value = Cast::Float($value);

        if ($value == 0) {
            throw new \InvalidArgumentException('Division by zero');
        }

        if ($this->value === null) {
            return new FloatType($this->value);
        }

        return new FloatType($this->value % $value);
    }

    /**
     * Exponentiation. Product is always FloatType.
     *
     * @param  mixed $value
     * @return FloatType
     */
    public function exp($value)
    {
        $value = Cast::Float($value);

        if ($this->value === null) {
            return new FloatType($this->value);
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
     * Square root. Product is always FloatType.
     *
     * @return FloatType
     */
    public function sqrt()
    {
        if ($this->value === null) {
            return new FloatType($this->value);
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
            return new FloatType($this->value);
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
