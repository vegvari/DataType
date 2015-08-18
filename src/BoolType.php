<?php

namespace Data\Type;

use Data\Type\Exceptions\InvalidBoolException;

class BoolType extends Type
{
    /**
     * @var string
     */
    const TYPE = 'bool';

    /**
     * Check the value
     *
     * @param  mixed     $value
     * @return bool|null
     */
    protected function check($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

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

            if ($value === null) {
                return null;
            }

            if ($value === 0 || $value === 0.0 || $value === '0' || $value === '0.0') {
                return false;
            }

            if ($value === 1 || $value === 1.0 || $value === '1' || $value === '1.0') {
                return true;
            }
        } else {
            if (is_array($value)) {
                throw new InvalidBoolException('Invalid bool: array');
            }

            if (is_resource($value)) {
                throw new InvalidBoolException('Invalid bool: resource');
            }

            if (is_object($value)) {
                throw new InvalidBoolException('Invalid bool: object');
            }
        }

        throw new InvalidBoolException('Invalid bool: "' . $value . '"');
    }
}
