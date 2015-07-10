<?php

namespace Data\Type;

use \Data\Type\Exceptions\InvalidFloatException;

class FloatType extends Number
{
    /**
     * Check the value
     *
     * @param  mixed      $value
     * @return float|null
     */
    protected function check($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

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

        if ($value instanceof FloatType) {
            $value = $value->value();

            if ($value === null) {
                return null;
            }

            return (float) $value;
        }

        if ($value instanceof Type) {
            $value = $value->value();

            if ($value === null) {
                return null;
            }

            if ($value === false || $value === '0' || $value === '0.0') {
                return 0.0;
            }

            if ($value === true || $value === '1' || $value === '1.0') {
                return 1.0;
            }
        } else {
            if (is_array($value)) {
                throw new InvalidFloatException('Invalid float: array');
            }

            if (is_resource($value)) {
                throw new InvalidFloatException('Invalid float: resource');
            }

            if (is_object($value)) {
                throw new InvalidFloatException('Invalid float: object');
            }
        }

        if (filter_var($value, FILTER_VALIDATE_FLOAT) === false) {
            throw new InvalidFloatException('Invalid float: "' . $value . '"');
        }

        return (float) $value;
    }
}
