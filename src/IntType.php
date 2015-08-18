<?php

namespace Data\Type;

use InvalidArgumentException;
use \Data\Type\Exceptions\InvalidIntException;
use \Data\Type\Exceptions\InvalidFloatException;

class IntType extends Number
{
    /**
     * @var string
     */
    const TYPE = 'int';

    /**
     * Check the value
     *
     * @param  mixed    $value
     * @return int|null
     */
    protected function check($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_int($value)) {
            return $value;
        }

        if ($value === false || $value === 0.0 || $value === '0' || $value === '0.0') {
            return 0;
        }

        if ($value === true || $value === 1.0 || $value === '1' || $value === '1.0') {
            return 1;
        }

        if ($value instanceof IntType) {
            return $value->value();
        }

        if ($value instanceof Type) {
            $value = $value->value();

            if ($value === null) {
                return null;
            }

            if ($value === false || $value === 0.0 || $value === '0' || $value === '0.0') {
                return 0;
            }

            if ($value === true || $value === 1.0 || $value === '1' || $value === '1.0') {
                return 1;
            }
        } else {
            if (is_array($value)) {
                throw new InvalidIntException('Invalid int: array');
            }

            if (is_resource($value)) {
                throw new InvalidIntException('Invalid int: resource');
            }

            if (is_object($value)) {
                throw new InvalidIntException('Invalid int: object');
            }
        }

        if (filter_var($value, FILTER_VALIDATE_FLOAT) === false || filter_var($value, FILTER_VALIDATE_INT) === false) {
            throw new InvalidIntException('Invalid int: "' . $value . '"');
        }

        return (int) $value;
    }

    /**
     * Is it even?
     *
     * @return bool
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
     * @return bool
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
     * @return bool
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
