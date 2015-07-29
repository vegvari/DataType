<?php

namespace Data\Type;

use SplSubject;
use SplObserver;
use SplObjectStorage;
use InvalidArgumentException;

abstract class Type implements SplSubject
{
    const STATE_BEFORE_CHANGE = 0;
    const STATE_AFTER_CHANGE  = 1;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var SplObjectStorage
     */
    protected $observers;

    /**
     * @var string
     */
    protected $state = self::STATE_BEFORE_CHANGE;

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
            $this->setStateBeforeChange();
            $this->value = $value;
            $this->setStateAfterChange();
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
     * True if the value is not null
     *
     * @return bool
     */
    final public function isNotNull()
    {
        return ! $this->isNull();
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
     * Set the state to STATE_BEFORE_CHANGE
     */
    final protected function setStateBeforeChange()
    {
        $this->state = self::STATE_BEFORE_CHANGE;
        $this->notify();
    }

    /**
     * Set the state to STATE_BEFORE_CHANGE
     */
    final protected function setStateAfterChange()
    {
        $this->state = self::STATE_AFTER_CHANGE;
        $this->notify();
    }

    /**
     * Get the state
     *
     * @return int|null
     */
    final public function getState()
    {
        return $this->state;
    }
}
