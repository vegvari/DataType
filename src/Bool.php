<?php

namespace Data\Type;

class Bool extends Type
{
    /**
     * @see Type
     */
    public function check($value)
    {
        $value = parent::check($value);

        if ($value === false || $value === 0 || $value === 0.0 || $value === '0') {
            return false;
        }

        if ($value === true || $value === 1 || $value === 1.0 || $value === '1') {
            return true;
        }

        throw new \InvalidArgumentException();
    }
}
