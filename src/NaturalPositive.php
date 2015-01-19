<?php

namespace Data\Type;

class NaturalPositive extends Natural
{
    /**
     * @see Basic
     */
    public function check($value)
    {
        $value = parent::check($value);

        if ($value < 1) {
            throw new \OutOfRangeException();
        }

        return $value;
    }

    /**
     * Generate random
     *
     * @param  mixed $min
     * @param  mixed $max
     * @return mixed
     */
    public static function rand($min = 1, $max = null)
    {
        return parent::rand($min, $max);
    }
}
