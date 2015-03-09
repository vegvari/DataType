<?php

namespace Data\Type;

class Bool extends Basic
{
    /**
     * @see TypeInterface
     */
    public function check($value)
    {
        if ($value === false || $value === 0 || $value === 0.0 || $value === '0') {
            return false;
        } elseif ($value === true || $value === 1 || $value === 1.0 || $value === '1') {
            return true;
        } elseif ($value instanceof Bool) {
            return $value->value();
        } elseif ($value instanceof Basic) {
            $value = $value->value();
        }

        if ($value === false || $value === 0 || $value === 0.0 || $value === '0') {
            return false;
        } elseif ($value === true || $value === 1 || $value === 1.0 || $value === '1') {
            return true;
        }

        throw new \InvalidArgumentException();
    }
}
