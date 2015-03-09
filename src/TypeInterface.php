<?php

namespace Data\Type;

interface TypeInterface
{
    /**
     * Get the value
     *
     * @return mixed
     */
    public function value();

    /**
     * Set the value
     *
     * @param  mixed $value
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
