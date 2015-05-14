<?php

namespace Data\Type;

use InvalidArgumentException;

class IntType extends FloatType
{
    /**
     * Check the value
     *
     * @param  mixed $value
     * @return int
     */
    protected function check($value)
    {
        if (is_int($value)) {
            return $value;
        }

        if ($value === false || $value === 0.0 || $value === '0') {
            return 0;
        }

        if ($value === true || $value === 1.0 || $value === '1') {
            return 1;
        }

        if ($value instanceof IntType) {
            return $value->value();
        }

        if ($value instanceof Type) {
            $value = $value->value();
        }

        try {
            $value = parent::check($value);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException('Invalid int');
        }

        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            throw new InvalidArgumentException('Invalid int');
        }

        return (int) $value;
    }

    /**
     * Is it even?
     *
     * @return boolean
     */
    public function isEven()
    {
        if ($this->value % 2 === 0) {
            return true;
        }

        return false;
    }

    /**
     * Is it odd?
     *
     * @return boolean
     */
    public function isOdd()
    {
        if ($this->value % 2 !== 0) {
            return true;
        }

        return false;
    }

    /**
     * Is this a prime?
     *
     * @return boolean
     */
    public function isPrime()
    {
        if ($this->value < 2) {
            return false;
        }

        if ($this->value === 2) {
            return true;
        }

        if ($this->isEven()) {
            return false;
        }

        for ($i = 3; $i <= ceil(sqrt($this->value)); $i = $i + 2) {
            if ($this->value % $i == 0) {
                return false;
            }
        }

        return true;
    }
}
