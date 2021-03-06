<?php

namespace Data\Type;

use SplSubject;
use Data\Type\Traits\SplSubject as SplSubjectTrait;

abstract class Type implements SplSubject
{
    use SplSubjectTrait;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * Constructor
     *
     * @param mixed $value
     */
    public function __construct($value = null)
    {
        $this->set($value);
    }

    /**
     * Set the value
     *
     * @param  mixed $value
     * @return this
     */
    public function set($value)
    {
        if ($value !== null) {
            $value = $this->check($value);
        }

        if ($value !== $this->value) {
            $this->value = $value;
            $this->notify();
        }

        return $this;
    }

    /**
     * Get the value
     *
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Check the value
     *
     * @param  mixed $value
     * @return mixed
     */
    protected function check($value)
    {
        if ($value instanceof Type) {
            $value = $value->value;
        }

        return $value;
    }

    /**
     * Cast instance to string
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->value === false) {
            return '0';
        }

        return (string) $this->value;
    }
}
