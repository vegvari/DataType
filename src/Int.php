<?php

namespace Data\Type;

class Int extends Float
{
    /**
     * @see Type
     */
    public function check($value)
    {
        $value = parent::check($value);

        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            throw new \InvalidArgumentException();
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

        if($this->value === 2) {
            return true;
        }

        if($this->isEven()) {
            return false;
        }

        for($i = 3; $i <= ceil(sqrt($this->value)); $i = $i + 2) {
            if($this->value % $i == 0) {
                return false;
            }
        }

        return true;
    }
}
