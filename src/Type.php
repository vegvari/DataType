<?php

namespace Data\Type;

use SplSubject;
use SplObserver;
use SplObjectStorage;

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
    public function set($value)
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
    public function isNull()
    {
        if ($this->value === null) {
            return true;
        }

        return false;
    }

    /**
     * True if the value is not null
     *
     * @return bool
     */
    public function isNotNull()
    {
        if ($this->value !== null) {
            return true;
        }

        return false;
    }

    /**
     * Cast instance to string
     *
     * @return string
     */
    public function __toString()
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
    public function attach(SplObserver $observer)
    {
        if ($this->observers === null) {
            $this->observers = new SplObjectStorage();
        }

        $this->observers->attach($observer);

        if ($this->value !== null) {
            $this->notify();
        }
    }

    /**
     * @see SplObserver
     */
    public function detach(SplObserver $observer)
    {
        if ($this->observers !== null) {
            $this->observers->detach($observer);
        }
    }

    /**
     * @see SplObserver
     */
    public function notify()
    {
        if ($this->observers !== null) {
            foreach ($this->observers as $observer) {
                $observer->update($this);
            }
        }
    }
}
