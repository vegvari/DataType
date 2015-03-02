<?php

namespace Data\Type;

class Float extends Basic
{
    public static function castNatural($value)
    {
        $class = get_called_class();

        $instance = new $class($value);

        if ($instance->value < 0) {
            throw new \InvalidArgumentException();
        }

        return $instance->value;
    }

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
     * Generate random
     *
     * @param  mixed $min
     * @param  mixed $max
     * @return mixed
     */
    public static function rand($min = 0, $max = null)
    {
        $min = self::cast($min);

        try {
            $max = self::cast($max);
        } catch (\InvalidArgumentException $e) {
            $max = mt_getrandmax();
        }

        mt_srand();

        return self::make(mt_rand($min, $max));
    }

    /**
     * @see Basic
     */
    public function check($value)
    {
        $value = parent::check($value);

        if ($value === false) {
            return 0.0;
        }

        if (filter_var($value, FILTER_VALIDATE_FLOAT) === false) {
            throw new \InvalidArgumentException();
        }

        return (float) $value;
    }

    /**
     * Negation
     *
     * @return Basic
     */
    public function neg()
    {
        return self::make(-$this->value);
    }

    /**
     * Addition. Product is always Float.
     *
     * @param  mixed $value
     * @return Float
     */
    public function add($value)
    {
        return self::make($this->value + Float::cast($value));
    }

    /**
     * Subtraction. Product is always Float.
     *
     * @param  mixed $value
     * @return Float
     */
    public function sub($value)
    {
        return Float::make($this->value - Float::cast($value));
    }

    /**
     * Multiplication. Product is always Float.
     *
     * @param  mixed $value
     * @return Float
     */
    public function mul($value)
    {
        return Float::make($this->value * Float::cast($value));
    }

    /**
     * Division. Product is always Float.
     *
     * @param  mixed $value
     * @return Float
     */
    public function div($value)
    {
        return Float::make($this->value / Float::cast($value));
    }

    /**
     * Modulus. Product is always Float.
     *
     * @param  mixed $value
     * @return Float
     */
    public function mod($value)
    {
        return Float::make($this->value % Float::cast($value));
    }

    /**
     * Exponentiation. Product is always Float.
     *
     * @param  mixed $value
     * @return Float
     */
    public function exp($value)
    {
        return Float::make(pow($this->value, Float::cast($value)));
    }

    /**
     * Square root. Product is always Float.
     *
     * @return Float
     */
    public function sqrt()
    {
        return Float::make(sqrt($this->value));
    }

    /**
     * Nth root. Product is always Float.
     *
     * @return Float
     */
    public function root($value)
    {
        return Float::make(pow($this->value, 1 / Float::cast($value)));
    }
}
