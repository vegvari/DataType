<?php

namespace Data\Type;

class _float extends Number
{
    /**
     * @see TypeInterface
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

        if ($value instanceof _int) {
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

    public function check2($value)
    {
        if ($value === false || $value === 0 || $value === 0.0 || $value === '0') {
            return 0.0;
        } elseif ($value === true || $value === 1 || $value === 1.0 || $value === '1') {
            return 1.0;
        } elseif (is_float($value)) {
            return $value;
        } elseif ($value instanceof Float) {
            return (float) $value->value();
        } elseif ($value instanceof Basic) {
            $value = $value->value();
        }

        if (filter_var($value, FILTER_VALIDATE_FLOAT) === false) {
            throw new \InvalidArgumentException('Invalid float');
        }

        return (float) $value;
    }
}
