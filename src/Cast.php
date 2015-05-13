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
    public static function __callStatic($name, array $args = array())
    {
        if ( ! isset ($args[0])) {
            $args[0] = null;
        }

        // Not nullable
        switch ($name) {
            case 'Bool':
            case 'Float':
            case 'uFloat':
            case 'pFloat':
            case 'nFloat':
            case 'Int':
            case 'uInt':
            case 'pInt':
            case 'nInt':
            case 'String':
                if ($args[0] === null) {
                    throw new InvalidArgumentException($name . ' is not nullable');
                }
                break;
        }

        // Nullable
        switch ($name) {
            case '_Bool':
            case '_Float':
            case '_uFloat':
            case '_pFloat':
            case '_nFloat':
            case '_Int':
            case '_uInt':
            case '_pInt':
            case '_nInt':
            case '_String':
                if ($args[0] === null) {
                    return;
                } else {
                    $name = substr($name, 1);
                }
                break;
        }

        switch ($name) {
            case 'Bool':
                $instance = new BoolType($args[0]);
                return $instance->value();
                break;

            case 'Float':
                $instance = new FloatType($args[0]);
                return $instance->value();
                break;

            case 'uFloat':
                $instance = new FloatType($args[0]);

                if ($instance->value() < 0) {
                    throw new InvalidArgumentException('Unsigned float must be >= 0, "' . $instance->value() . '" given');
                }

                return $instance->value();
                break;

            case 'pFloat':
                $instance = new FloatType($args[0]);

                if ($instance->value() <= 0) {
                    throw new InvalidArgumentException('Positive float must be > 0, "' . $instance->value() . '" given');
                }

                return $instance->value();
                break;

            case 'nFloat':
                $instance = new FloatType($args[0]);

                if ($instance->value() >= 0) {
                    throw new InvalidArgumentException('Negative float must be < 0, "' . $instance->value() . '" given');
                }

                return $instance->value();
                break;

            case 'Int':
                $instance = new IntType($args[0]);
                return $instance->value();
                break;

            case 'uInt':
                $instance = new IntType($args[0]);

                if ($instance->value() < 0) {
                    throw new InvalidArgumentException('Unsigned int must be >= 0, "' . $instance->value() . '" given');
                }

                return $instance->value();
                break;

            case 'pInt':
                $instance = new IntType($args[0]);

                if ($instance->value() < 1) {
                    throw new InvalidArgumentException('Positive int must be > 0, "' . $instance->value() . '" given');
                }

                return $instance->value();
                break;

            case 'nInt':
                $instance = new IntType($args[0]);

                if ($instance->value() >= 0) {
                    throw new InvalidArgumentException('Negative int must be < 0, "' . $instance->value() . '" given');
                }

                return $instance->value();
                break;

            case 'String':
                if ( ! isset ($args[1])) {
                    $instance = new StringType($args[0]);
                } else {
                    $instance = new StringType($args[0], $args[1]);
                }

                return $instance->value();
                break;

            default:
                throw new Exception('Unknown type: "' . $name . '"');
                break;
        }
    }
}
