<?php

namespace Data\Type;

use Exception;
use InvalidArgumentException;

abstract class Cast
{
    /**
     * Casting to basic types
     *
     * @param  string $name
     * @param  array  $args
     * @return bool|float|int|string|null
     */
    public static function __callStatic($name, array $args = [])
    {
        $nullable = false;
        if (substr($name, 0, 1) === '_') {
            $nullable = true;
            $name = substr($name, 1);
        }

        if (! isset($args[0])) {
            $args[0] = null;
        }

        switch ($name) {
            case 'Bool':
                $instance = new BoolType($args[0]);
                break;

            case 'Float':
            case 'uFloat':
            case 'pFloat':
            case 'nFloat':
                $instance = new FloatType($args[0]);
                break;

            case 'Int':
            case 'uInt':
            case 'pInt':
            case 'nInt':
                $instance = new IntType($args[0]);
                break;

            case 'String':
                if (! isset($args[1])) {
                    $instance = new StringType($args[0]);
                } else {
                    $instance = new StringType($args[0], $args[1]);
                }
                break;

            case 'DateTime':
                if (! isset($args[1])) {
                    $instance = new DateTimeType($args[0]);
                } else {
                    $instance = new DateTimeType($args[0], $args[1]);
                }
                break;

            default:
                throw new Exception('Unknown type: "' . $name . '"');
                break;
        }

        if ($nullable === false && $instance->isNull()) {
            throw new InvalidArgumentException($name . ' is not nullable');
        }

        if ($nullable === true && $instance->isNull()) {
            return;
        }

        switch ($name) {
            case 'Float':
                $min = null;
                if (isset($args[1]) && $args[1] !== null) {
                    $min = static::_Float($args[1]);
                }

                $max = null;
                if (isset($args[2])) {
                    $max = static::_Float($args[2]);
                }

                if ($min !== null && $max !== null && $min > $max) {
                    throw new InvalidArgumentException('Min must be less than max: "' . $min . '", "' . $max . '"');
                }

                if ($min !== null && $instance->value() < $min) {
                    throw new InvalidArgumentException('Value less than min (' . $min . '): ' . '"' . $instance . '"');
                }

                if ($max !== null && $instance->value() > $max) {
                    throw new InvalidArgumentException('Value greater than max (' . $max . '): ' . '"' . $instance . '"');
                }
                break;

            case 'uFloat':
                if ($instance->value() < 0) {
                    throw new InvalidArgumentException('Unsigned float must be >= 0, "' . $instance . '" given');
                }
                break;

            case 'pFloat':
                if ($instance->value() <= 0) {
                    throw new InvalidArgumentException('Positive float must be > 0, "' . $instance . '" given');
                }
                break;

            case 'nFloat':
                if ($instance->value() >= 0) {
                    throw new InvalidArgumentException('Negative float must be < 0, "' . $instance->value() . '" given');
                }
                break;

            case 'Int':
                $min = null;
                if (isset($args[1]) && $args[1] !== null) {
                    $min = static::_Int($args[1]);
                }

                $max = null;
                if (isset($args[2])) {
                    $max = static::_Int($args[2]);
                }

                if ($min !== null && $max !== null && $min > $max) {
                    throw new InvalidArgumentException('Min must be less than max: "' . $min . '", "' . $max . '"');
                }

                if ($min !== null && $instance->value() < $min) {
                    throw new InvalidArgumentException('Value less than min (' . $min . '): ' . '"' . $instance . '"');
                }

                if ($max !== null && $instance->value() > $max) {
                    throw new InvalidArgumentException('Value greater than max (' . $max . '): ' . '"' . $instance . '"');
                }
                break;

            case 'uInt':
                if ($instance->lt(0)) {
                    throw new InvalidArgumentException('Unsigned int must be >= 0, "' . $instance->value() . '" given');
                }
                break;

            case 'pInt':
                if ($instance->value() < 1) {
                    throw new InvalidArgumentException('Positive int must be > 0, "' . $instance->value() . '" given');
                }
                break;

            case 'nInt':
                if ($instance->value() >= 0) {
                    throw new InvalidArgumentException('Negative int must be < 0, "' . $instance->value() . '" given');
                }
                break;
        }

        return $instance->value();
    }
}
