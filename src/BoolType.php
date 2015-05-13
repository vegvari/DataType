<?php

namespace Data\Type;

use InvalidArgumentException;

class BoolType extends Type
{
    /**
     * Check the value
     *
     * @param  mixed $value
     * @return bool
     */
    protected function check($value)
    {
        if ($value === false || $value === 0 || $value === 0.0 || $value === '0') {
            return false;
        }

        if ($value === true || $value === 1 || $value === 1.0 || $value === '1') {
            return true;
        }

        if ($value instanceof BoolType) {
            return $value->value();
        }

        if ($value instanceof Type) {
            $value = $value->value();
        }

        if ($value === 0 || $value === 0.0 || $value === '0') {
            return false;
        }

        if ($value === 1 || $value === 1.0 || $value === '1') {
            return true;
        }

        throw new InvalidArgumentException('Invalid bool');
    }
}
