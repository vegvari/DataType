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

        return self::make(mt_rand($min, $max));
    }

    /**
     * Return the value if the value is 0 or greater than 0
     *
     * @param  mixed $value
     * @return Number
     */
    public static function castNatural($value)
    {
        $class = get_called_class();

        $instance = new $class($value);

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
        $class = get_called_class();

        $instance = new $class($value);

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
        $class = get_called_class();

        $instance = new $class($value);

        $value = (float) $instance->value;
        if ($value > 0 || $value === 0.0) {
            throw new \InvalidArgumentException();
        }

        return $instance->value;
    }

    /**
     * Negation. Product is always Float.
     *
     * @return Float
     */
    public function neg()
    {
        if ($this->value === null) {
            return Float::make($this->value);
        }

        return Float::make($this->value * -1);
    }

    /**
     * Addition. Product is always Float.
     *
     * @param  mixed $value
     * @return Float
     */
    public function add($value)
    {
        $value = self::cast($value);

        if ($this->value === null) {
            return Float::make($this->value);
        }

        return Float::make($this->value + $value);
    }

    /**
     * Subtraction. Product is always Float.
     *
     * @param  mixed $value
     * @return Float
     */
    public function sub($value)
    {
        $value = self::cast($value);

        if ($this->value === null) {
            return Float::make($this->value);
        }

        return Float::make($this->value - $value);
    }

    /**
     * Multiplication. Product is always Float.
     *
     * @param  mixed $value
     * @return Float
     */
    public function mul($value)
    {
        $value = self::cast($value);

        if ($this->value === null) {
            return Float::make($this->value);
        }

        return Float::make($this->value * $value);
    }

    /**
     * Division. Product is always Float.
     *
     * @param  mixed $value
     * @return Float
     */
    public function div($value)
    {
        $value = self::cast($value);

        if ($value == 0) {
            throw new \InvalidArgumentException('Division by zero');
        }

        if ($this->value === null) {
            return Float::make($this->value);
        }

        return Float::make($this->value / $value);
    }

    /**
     * Modulus. Product is always Float.
     *
     * @param  mixed $value
     * @return Float
     */
    public function mod($value)
    {
        $value = self::cast($value);

        if ($value == 0) {
            throw new \InvalidArgumentException('Division by zero');
        }

        if ($this->value === null) {
            return Float::make($this->value);
        }

        return Float::make($this->value % $value);
    }

    /**
     * Exponentiation. Product is always Float.
     *
     * @param  mixed $value
     * @return Float
     */
    public function exp($value)
    {
        $value = self::cast($value);

        if ($this->value === null) {
            return Float::make($this->value);
        }

        return Float::make(pow($this->value, $value));
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
     * Square root. Product is always Float.
     *
     * @return Float
     */
    public function sqrt()
    {
        if ($this->value === null) {
            return Float::make($this->value);
        }

        return Float::make(sqrt($this->value));
    }

    /**
     * Nth root. Product is always Float.
     *
     * @return Float
     */
    public function root($value)
    {
        $value = self::cast($value);

        if ($this->value === null) {
            return Float::make($this->value);
        }

        return Float::make(pow($this->value, 1 / $value));
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
