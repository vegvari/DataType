<?php

namespace Data\Type;

class DateTime extends \Carbon\Carbon implements \SplSubject
{
    use SplSubject;

    protected $value;

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
     * @see DateTime::setDate
     */
    public function setDate($year, $month, $day)
    {
        $result = parent::setDate($year, $month, $day);
        $this->notify();
        $this->value = (string) $this;
        return $result;
    }

    /**
     * @see DateTime::setISODate
     */
    public function setISODate($year, $week, $day = null)
    {
        $result = parent::setISODate($year, $month, $day);
        $this->notify();
        $this->value = (string) $this;
        return $result;
    }

    /**
     * @see DateTime::setTime
     */
    public function setTime($hour, $minute, $second = null)
    {
        $result = parent::setTime($hour, $minute, $second);
        $this->notify();
        $this->value = (string) $this;
        return $result;
    }

    /**
     * @see DateTime::setTimestamp
     */
    public function setTimestamp($unixtimestamp)
    {
        $result = parent::setTimestamp($unixtimestamp);
        $this->notify();
        $this->value = (string) $this;
        return $result;
    }

    /**
     * @see DateTime::setTimezone
     */
    public function setTimezone($value)
    {
        $result = parent::setTimezone($value);
        $this->notify();
        $this->value = (string) $this;
        return $result;
    }

    /**
     * @see DateTime::modify
     */
    public function modify($modify)
    {
        $result = parent::modify($modify);
        $this->notify();
        $this->value = (string) $this;
        return $result;
    }

    /**
     * @see DateTime::add
     */
    public function add($interval)
    {
        $result = parent::add($interval);
        $this->notify();
        $this->value = (string) $this;
        return $result;
    }

    /**
     * @see DateTime::sub
     */
    public function sub($interval)
    {
        $result = parent::sub($interval);
        $this->notify();
        $this->value = (string) $this;
        return $result;
    }
}
