<?php

namespace Data\Type;

use SplSubject;
use SplObserver;
use SplObjectStorage;
use InvalidArgumentException;

abstract class Type implements SplSubject
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var SplObjectStorage
     */
    protected $observers;

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
    final public function set($value)
    {
        $value = $this->check($value);

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
    abstract protected function check($value);

    /**
     * True if the value is null
     *
     * @return bool
     */
    final public function isNull()
    {
        if ($this->value === null) {
            return true;
        }

        return false;
    }

    /**
     * Cast instance to string
     *
     * @return string
     */
    final public function __toString()
    {
        if ($this->value === null) {
            return '';
        }

        if ($this->value === false) {
            return '0';
        }

        return (string) $this->value;
    }

    /**
     * @see SplObserver
     */
    final public function attach(SplObserver $observer)
    {
        if ($this->observers === null) {
            $this->observers = new SplObjectStorage();
        }

        $this->observers->attach($observer);
    }

    /**
     * @see SplObserver
     */
    final public function detach(SplObserver $observer)
    {
        if ($this->observers !== null) {
            $this->observers->detach($observer);
        }
    }

    /**
     * @see SplObserver
     */
    final public function notify()
    {
        if ($this->observers !== null) {
            foreach ($this->observers as $observer) {
                $observer->update($this);
            }
        }
    }

    /**
     * Delete the observers of the clone
     */
    public function __clone()
    {
        $this->observers = null;
    }
}
