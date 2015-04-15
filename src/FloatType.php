<?php

namespace Data\Type;

class FloatType extends Number
{
    /**
     * Check the value
     *
     * @param  mixed $value
     * @return float
     */
    public function check($value)
    {
        if (is_float($value)) {
            return $value;
        }

        if (is_int($value)) {
            return (float) $value;
        }

        if ($value === false || $value === '0' || $value === '0.0') {
            return 0.0;
        }

        if ($value === true || $value === '1' || $value === '1.0') {
            return 1.0;
        }

        if ($value == $this) {
            return $value->value();
        }

        if ($value instanceof IntType) {
            return (float) $value->value();
        }

        if ($value instanceof Basic) {
            $value = $value->value();
        }

        if (filter_var($value, FILTER_VALIDATE_FLOAT) === false) {
            throw new \InvalidArgumentException('Invalid float');
        }

        return (float) $value;
    }
}
