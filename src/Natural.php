<?php

namespace Data\Type;

class Natural extends Int
{
    /**
     * @see Basic
     */
    public function check($value)
    {
        $value = parent::check($value);

        if ($value < 0) {
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
    public static function rand($min = 0, $max = null)
    {
        $min = Natural::cast($min);

        return parent::rand($min, $max);
    }
}
