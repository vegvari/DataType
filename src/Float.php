<?php

namespace Data\Type;

class Float extends Type
{
    /**
     * @see Type
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
     * Generate random
     *
     * @param  mixed $min
     * @param  mixed $max
     * @return mixed
     */
    public static function rand($min = 0, $max = null)
    {
        try {
            $max = self::cast($max);
        } catch (\InvalidArgumentException $e) {
            $max = mt_getrandmax();
        }

        mt_srand();

        return self::create(mt_rand(self::cast($min), $max));
    }

    /**
     * Negation
     *
     * @return Type
     */
    public function neg()
    {
        return self::create(-$this->value);
    }

    /**
     * Addition. Product is always Float.
     *
     * @param  mixed $value
     * @return Float
     */
    public function add($value)
    {
        return self::create($this->value + Float::cast($value));
    }

    /**
     * Subtraction. Product is always Float.
     *
     * @param  mixed $value
     * @return Float
     */
    public function sub($value)
    {
        return Float::create($this->value - Float::cast($value));
    }

    /**
     * Multiplication. Product is always Float.
     *
     * @param  mixed $value
     * @return Float
     */
    public function mul($value)
    {
        return Float::create($this->value * Float::cast($value));
    }

    /**
     * Division. Product is always Float.
     *
     * @param  mixed $value
     * @return Float
     */
    public function div($value)
    {
        return Float::create($this->value / Float::cast($value));
    }

    /**
     * Modulus. Product is always Float.
     *
     * @param  mixed $value
     * @return Float
     */
    public function mod($value)
    {
        return Float::create($this->value % Float::cast($value));
    }

    /**
     * Exponentiation. Product is always Float.
     *
     * @param  mixed $value
     * @return Float
     */
    public function exp($value)
    {
        return Float::create(pow($this->value, Float::cast($value)));
    }

    /**
     * Square root. Product is always Float.
     *
     * @return Float
     */
    public function sqrt()
    {
        return Float::create(sqrt($this->value));
    }

    /**
     * Nth root. Product is always Float.
     *
     * @return Float
     */
    public function root($value)
    {
        return Float::create(pow($this->value, 1 / Float::cast($value)));
    }
}
