<?php

namespace Data\Type;

class Natural extends Int
{
    /**
     * @see TypeInterface
     */
    public function check($value)
    {
        $value = parent::check($value);

        if ($value < 0) {
            throw new \OutOfRangeException();
        }

        return $value;
    }
}
