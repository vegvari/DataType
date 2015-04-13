<?php

namespace Data\Type;

abstract class Number extends Basic
{
    /**
     * Creates an instance with a random number in it
     *
     * @param  mixed $min
     * @param  mixed $max
     * @return Number
     */
    public static function rand($min = 0, $max = null)
    {
        $min = self::cast($min);
        $max = self::castNullable($max);

        if ($max === null) {
            $max = mt_getrandmax();
        }

        mt_srand();

        return self::create(mt_rand($min, $max));
    }

    /**
     * Return the value if the value is 0 or greater than 0
     *
     * @param  mixed $value
     * @return Number
     */
    public static function castNatural($value)
    {
        $instance = new static($value);

        if ($instance->value < 0) {
            throw new \InvalidArgumentException();
        }

        return $instance->value;
    }

    /**
     * Return the value if the value is greater than 0
     *
     * @param  mixed $value
     * @return Number
     */
    public static function castPositive($value)
    {
        $instance = new static($value);

        $value = (float) $instance->value;
        if ($value < 0 || $value === 0.0) {
            throw new \InvalidArgumentException();
        }

        return $instance->value;
    }

    /**
     * Return the value if the value is lower than 0
     *
     * @param  mixed $value
     * @return Number
     */
    public static function castNegative($value)
    {
        $instance = new static($value);

        $value = (float) $instance->value;
        if ($value > 0 || $value === 0.0) {
            throw new \InvalidArgumentException();
        }

        return $instance->value;
    }

    /**
     * Negation. Product is always _float.
     *
     * @return _float
     */
    public function neg()
    {
        if ($this->value === null) {
            return _float::create($this->value);
        }

        return _float::create($this->value * -1);
    }

    /**
     * Addition. Product is always _float.
     *
     * @param  mixed $value
     * @return _float
     */
    public function add($value)
    {
        $value = self::cast($value);

        if ($this->value === null) {
            return _float::create($this->value);
        }

        return _float::create($this->value + $value);
    }

    /**
     * Subtraction. Product is always _float.
     *
     * @param  mixed $value
     * @return _float
     */
    public function sub($value)
    {
        $value = self::cast($value);

        if ($this->value === null) {
            return _float::create($this->value);
        }

        return _float::create($this->value - $value);
    }

    /**
     * Multiplication. Product is always _float.
     *
     * @param  mixed $value
     * @return _float
     */
    public function mul($value)
    {
        $value = self::cast($value);

        if ($this->value === null) {
            return _float::create($this->value);
        }

        return _float::create($this->value * $value);
    }

    /**
     * Division. Product is always _float.
     *
     * @param  mixed $value
     * @return _float
     */
    public function div($value)
    {
        $value = self::cast($value);

        if ($value == 0) {
            throw new \InvalidArgumentException('Division by zero');
        }

        if ($this->value === null) {
            return _float::create($this->value);
        }

        return _float::create($this->value / $value);
    }

    /**
     * Modulus. Product is always _float.
     *
     * @param  mixed $value
     * @return _float
     */
    public function mod($value)
    {
        $value = self::cast($value);

        if ($value == 0) {
            throw new \InvalidArgumentException('Division by zero');
        }

        if ($this->value === null) {
            return _float::create($this->value);
        }

        return _float::create($this->value % $value);
    }

    /**
     * Exponentiation. Product is always _float.
     *
     * @param  mixed $value
     * @return _float
     */
    public function exp($value)
    {
        $value = self::cast($value);

        if ($this->value === null) {
            return _float::create($this->value);
        }

        return _float::create(pow($this->value, $value));
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
     * Square root. Product is always _float.
     *
     * @return _float
     */
    public function sqrt()
    {
        if ($this->value === null) {
            return _float::create($this->value);
        }

        return _float::create(sqrt($this->value));
    }

    /**
     * Nth root. Product is always _float.
     *
     * @return _float
     */
    public function root($value)
    {
        $value = self::cast($value);

        if ($this->value === null) {
            return _float::create($this->value);
        }

        return _float::create(pow($this->value, 1 / $value));
    }

    /**
     * Equal
     *
     * @param  mixed $value
     * @return bool
     */
    public function eq($value)
    {
        $value = self::cast($value);

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
        $value = self::cast($value);

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
        $value = self::cast($value);

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
        $value = self::cast($value);

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
        $value = self::cast($value);

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
        $value = self::cast($value);

        if ($this->value !== null && $this->value <= $value) {
            return true;
        }

        return false;
    }
}
