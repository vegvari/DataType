<?php

namespace Data\Type;

interface BasicInterface
{
    /**
     * Constructor
     *
     * @param mixed $value
     */
    public function __construct($value = null);

    /**
     * Create a new instance
     *
     * @param  mixed $value
     * @return Type
     */
    public static function make($value = null);

    /**
     * Create a new instance and return the value itself
     *
     * @param  mixed $value
     * @return mixed
     */
    public static function cast($value);

    /**
     * Create a new instance and return the value. Accepts null.
     *
     * @param  mixed $value
     * @return mixed
     */
    public static function castNullable($value);

    /**
     * Null if the value invalid
     *
     * @param  mixed $value
     * @return mixed
     */
    public static function castSilent($value);

    /**
     * Return the value
     *
     * @return mixed
     */
    public function __get($name);

    /**
     * Create a new instance
     *
     * @return mixed
     */
    public function set($value);

    /**
     * Check the value
     *
     * @param  mixed $value
     * @return mixed
     */
    public function check($value);

    /**
     * Cast instance to string
     *
     * @return string
     */
    public function __toString();
}
