<?php

namespace Data\Type;

interface TypeInterface
{
    /**
     * Constructor
     *
     * @param Mixed
     */
    public function __construct($value);

    /**
     * Create a new instance
     *
     * @param  Mixed $value
     * @return Type
     */
    public static function create($value);

    /**
     * Create a new instance and return the value itself
     *
     * @param  Mixed $value
     * @return Mixed
     */
    public static function cast($value);

    /**
     * Accept null
     *
     * @param  Mixed $value
     * @return Mixed
     */
    public static function castNullable($value);

    /**
     * Return null if the value invalid
     *
     * @param  Mixed $value
     * @return Mixed
     */
    public static function castSilent($value);

    /**
     * Check the value
     *
     * @param  Mixed $value
     * @return Mixed
     */
    public function check($value);

    /**
     * Return the value
     *
     * @return Mixed
     */
    public function value();
}
