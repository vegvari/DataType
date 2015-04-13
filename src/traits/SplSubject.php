<?php

namespace Data\Type\Traits;

trait SplSubject
{
    /**
     * @var SplObjectStorage
     */
    protected $observers;

    /**
     * @see SplObserver
     */
    public function attach(\SplObserver $observer)
    {
        if ($this->observers === null) {
            $this->observers = new \SplObjectStorage();
        }

        $this->observers->attach($observer);

        if ($this->value !== null) {
            $this->notify();
        }
    }

    /**
     * @see SplObserver
     */
    public function detach(\SplObserver $observer)
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
