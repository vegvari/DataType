<?php

namespace Data\Type;

use DateTime;
use DateTimeZone;

use Exception;
use InvalidArgumentException;

class DateTimeType extends Type
{
    /**
     * @var DateTimeZone
     */
    protected $timezone;

    /**
     * @var DateTime
     */
    protected $datetime;

    /**
     * Constructor
     *
     * @param mixed  $value
     * @param string $format
     */
    public function __construct($value = null, $timezone = null)
    {
        if ($timezone instanceof DateTimeZone) {
            $this->timezone = $timezone;
            $timezone = $timezone->getName();
        } else {
            if ($timezone === null) {
                $timezone = date_default_timezone_get();
            }

            $this->timezone = new DateTimeZone($timezone);
        }

        if (self::isTimezoneDeprecated($timezone)) {
            throw new InvalidArgumentException('Timezone is deprecated: "' . $timezone . '"');
        }

        $this->set($value);
    }

    /**
     * Format the value
     *
     * @return string|null
     */
    public function format($value)
    {
        if ($this->isNotNull()) {
            return (string) $this->datetime->format($value);
        }
    }

    /**
     * Check the value
     *
     * @param  mixed       $value
     * @return string|null
     */
    protected function check($value)
    {
        if ($value === null || $value === '') {
            $this->datetime = null;
            return;
        }

        if ($value === 'now') {
            $microsecond = sprintf('%06d', round((microtime(true) - time()) * 1000000));
            $this->datetime = DateTime::createFromFormat('Y-m-d H:i:s.u', date('Y-m-d H:i:s.' . $microsecond), $this->timezone);
            $this->datetime->microsecond = (int) $microsecond;
        } elseif ($value instanceof static) {
            if ($value->isNull()) {
                $this->datetime = null;
                return;
            }

            $this->datetime = DateTime::createFromFormat('Y-m-d H:i:s.u', $value->format('Y-m-d H:i:s.u'), $this->timezone);
            $this->datetime->microsecond = (int) $value->format('u');
        } elseif ($value instanceof DateTime) {
            $this->datetime = $value;
            $this->datetime->setTimezone($this->timezone);
            $this->datetime->microsecond = (int) $value->format('u');
        } else {
            if ($value instanceof Type) {
                if ($value->isNull()) {
                    $this->datetime = null;
                    return;
                }
                $value = $value->value();
            }

            try {
                $this->datetime = new DateTime($value, $this->timezone);
            } catch (Exception $e) {
                throw new InvalidArgumentException('Invalid datetime: "' . $value . '"');
            }

            $this->datetime->microsecond = (int) $this->datetime->format('u');
        }

        return $this->datetime->format('Y-m-d H:i:s');
    }

    /**
     * Set the date and time
     *
     * @param  int      $year
     * @param  int      $month
     * @param  int      $day
     * @param  int|null $hour
     * @param  int|null $minute
     * @param  int|null $second
     * @param  int|null $microsecond
     * @return static
     */
    public function setDateTime($year, $month, $day, $hour, $minute, $second, $microsecond)
    {
        if ($year === null) {
            $year = $this->getYear() !== null ? $this->getYear() : 0;
        }

        if ($month === null) {
            $month = $this->getMonth() !== null ? $this->getMonth() : 1;
        }

        if ($day === null) {
            $day = $this->getDay() !== null ? $this->getDay() : 1;
        }

        if ($hour === null) {
            $hour = $this->getHour() !== null ? $this->getHour() : 0;
        }

        if ($minute === null) {
            $minute = $this->getMinute() !== null ? $this->getMinute() : 0;
        }

        if ($second === null) {
            $second = $this->getSecond() !== null ? $this->getSecond() : 0;
        }

        if ($microsecond === null) {
            $microsecond = $this->getMicrosecond() !== null ? $this->getMicrosecond() : 0;
        }

        try {
            $year = Cast::Int($year);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException('Invalid year: "' . $year . '"');
        }

        try {
            $month = Cast::Int($month, 1, 12);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException('Invalid month: "' . $month . '"');
        }

        try {
            $day = Cast::Int($day, 1, 31);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException('Invalid day: "' . $day . '"');
        }

        try {
            $hour = Cast::Int($hour, 0, 23);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException('Invalid hour: "' . $hour . '"');
        }

        try {
            $minute = Cast::Int($minute, 0, 59);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException('Invalid minute: "' . $minute . '"');
        }

        try {
            $second = Cast::Int($second, 0, 59);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException('Invalid second: "' . $second . '"');
        }

        try {
            $microsecond = Cast::Int($microsecond, 0, 999999);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException('Invalid microsecond: "' . $microsecond . '"');
        }

        $date = sprintf('%04d', $year) . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $day);
        $time = sprintf('%02d', $hour) . ':' . sprintf('%02d', $minute) . ':' . sprintf('%02d', $second) . '.' . sprintf('%06d', $microsecond);

        if ($month === 2 && $day === 29 && ! self::checkLeapYear($year)) {
            throw new InvalidArgumentException('Invalid date (Y-m-d): "' . $date . '"');
        }

        $this->set(DateTime::createFromFormat('Y-m-d H:i:s.u', $date . ' ' . $time));
        return $this;
    }

    /**
     * Set the date
     *
     * @param  int    $year
     * @param  int    $month
     * @param  int    $day
     * @return static
     */
    public function setDate($year, $month, $day)
    {
        $this->setDateTime($year, $month, $day, null, null, null, null);
        return $this;
    }

    /**
     * Set the time
     *
     * @param  int      $hour
     * @param  int      $minute
     * @param  int|null $second
     * @param  int|null $microsecond
     * @return static
     */
    public function setTime($hour, $minute, $second = null, $microsecond = null)
    {
        $this->setDateTime(null, null, null, $hour, $minute, $second, $microsecond);
        return $this;
    }

    /**
     * Set the year
     *
     * @param  int    $value
     * @return static
     */
    public function setYear($value)
    {
        $this->setDate($value, null, null);
        return $this;
    }

    /**
     * Set the month
     *
     * @param  int    $value
     * @return static
     */
    public function setMonth($value)
    {
        $this->setDate(null, $value, null);
        return $this;
    }

    /**
     * Set the day
     *
     * @param  int    $value
     * @return static
     */
    public function setDay($value)
    {
        $this->setDate(null, null, $value);
        return $this;
    }

    /**
     * Set the hour
     *
     * @param  int    $value
     * @return static
     */
    public function setHour($value)
    {
        $this->setTime($value, null, null, null);
        return $this;
    }

    /**
     * Set the minute
     *
     * @param  int    $value
     * @return static
     */
    public function setMinute($value)
    {
        $this->setTime(null, $value, null, null);
        return $this;
    }

    /**
     * Set the second
     *
     * @param  int    $value
     * @return static
     */
    public function setSecond($value)
    {
        $this->setTime(null, null, $value, null);
        return $this;
    }

    /**
     * Set the microsecond
     *
     * @param  int    $value
     * @return static
     */
    public function setMicrosecond($value)
    {
        $this->setTime(null, null, null, $value);
        return $this;
    }

    /**
     * Get a clone of the timezone
     *
     * @return DateTimeZone
     */
    public function getTimeZone()
    {
        return $this->timezone;
    }

    /**
     * Get a clone of the datetime
     *
     * @return DateTime|null
     */
    public function getDateTime()
    {
        if ($this->isNotNull()) {
            return clone $this->datetime;
        }
    }

    /**
     * Get the year
     *
     * @return int|null
     */
    public function getYear()
    {
        if ($this->isNotNull()) {
            return (int) $this->format('Y');
        }
    }

    /**
     * Get the month
     *
     * @return int|null
     */
    public function getMonth()
    {
        if ($this->isNotNull()) {
            return (int) $this->format('n');
        }
    }

    /**
     * Get the day
     *
     * @return int|null
     */
    public function getDay()
    {
        if ($this->isNotNull()) {
            return (int) $this->format('j');
        }
    }

    /**
     * Get the hour
     *
     * @return int|null
     */
    public function getHour()
    {
        if ($this->isNotNull()) {
            return (int) $this->format('G');
        }
    }

    /**
     * Get the minute
     *
     * @return int|null
     */
    public function getMinute()
    {
        if ($this->isNotNull()) {
            return (int) $this->format('i');
        }
    }

    /**
     * Get the second
     *
     * @return int|null
     */
    public function getSecond()
    {
        if ($this->isNotNull()) {
            return (int) $this->format('s');
        }
    }

    /**
     * Get the microsecond
     *
     * @return int|null
     */
    public function getMicrosecond()
    {
        if ($this->isNotNull()) {
            return $this->datetime->microsecond;
        }
    }

    /**
     * Get timestamp
     *
     * @return int|null
     */
    public function getTimestamp()
    {
        if ($this->isNotNull()) {
            return (int) $this->format('U');
        }
    }

    /**
     * Get the day of the week (ISO)
     * 1 - monday
     * 7 - sunday
     *
     * @return int|null
     */
    public function getDayOfWeek()
    {
        if ($this->isNotNull()) {
            return (int) $this->format('N');
        }
    }

    /**
     * Add year
     *
     * @param  int
     * @return static
     */
    public function addYear($value)
    {
        if ($this->isNotNull()) {
            $value = Cast::Int($value);
            $this->set($this->datetime->modify($value . ' year'));
        }

        return $this;
    }

    /**
     * Add month
     *
     * @param  int
     * @return static
     */
    public function addMonth($value)
    {
        if ($this->isNotNull()) {
            $value = Cast::Int($value);
            $this->set($this->datetime->modify($value . ' month'));
        }

        return $this;
    }

    /**
     * Add day
     *
     * @param  int
     * @return static
     */
    public function addDay($value)
    {
        if ($this->isNotNull()) {
            $value = Cast::Int($value);
            $this->set($this->datetime->modify($value . ' day'));
        }

        return $this;
    }

    /**
     * Add hour
     *
     * @param  int
     * @return static
     */
    public function addHour($value)
    {
        if ($this->isNotNull()) {
            $value = Cast::Int($value);
            $this->set($this->datetime->modify($value . ' hour'));
        }

        return $this;
    }

    /**
     * Add minute
     *
     * @param  int
     * @return static
     */
    public function addMinute($value)
    {
        if ($this->isNotNull()) {
            $value = Cast::Int($value);
            $this->set($this->datetime->modify($value . ' minute'));
        }

        return $this;
    }

    /**
     * Add second
     *
     * @param  int
     * @return static
     */
    public function addSecond($value)
    {
        if ($this->isNotNull()) {
            $value = Cast::Int($value);
            $this->set($this->datetime->modify($value . ' second'));
        }

        return $this;
    }

    /**
     * Add microsecond
     *
     * @param  int
     * @return static
     */
    public function addMicrosecond($value)
    {
        if ($this->isNotNull()) {
            $value = Cast::Int($value) + $this->getMicrosecond();
            $second = $value / 1000000;

            if ($second >= 1 || $second <= -1) {
                $second = (int) floor($second);
                $this->addSecond($second);

                $value = $value - $second * 1000000;
            }

            $this->setMicrosecond($value);
        }

        return $this;
    }

    /**
     * Sub year
     *
     * @param  int
     * @return static
     */
    public function subYear($value)
    {
        $this->addYear($value * -1);
        return $this;
    }

    /**
     * Sub month
     *
     * @param  int
     * @return static
     */
    public function subMonth($value)
    {
        $this->addMonth($value * -1);
        return $this;
    }

    /**
     * Sub day
     *
     * @param  int
     * @return static
     */
    public function subDay($value)
    {
        $this->addDay($value * -1);
        return $this;
    }

    /**
     * Sub hour
     *
     * @param  int
     * @return static
     */
    public function subHour($value)
    {
        $this->addHour($value * -1);
        return $this;
    }

    /**
     * Sub minute
     *
     * @param  int
     * @return static
     */
    public function subMinute($value)
    {
        $this->addMinute($value * -1);
        return $this;
    }

    /**
     * Sub second
     *
     * @param  int
     * @return static
     */
    public function subSecond($value)
    {
        $this->addSecond($value * -1);
        return $this;
    }

    /**
     * Sub microsecond
     *
     * @param  int
     * @return static
     */
    public function subMicrosecond($value)
    {
        $this->addMicrosecond($value * -1);
        return $this;
    }

    /**
     * Difference in years
     *
     * @param  mixed    $value
     * @return int|null
     */
    public function diffInYears($value)
    {
        if ($this->isNull()) {
            return;
        }

        if ($value instanceof DateTime) {
            return (int) $this->datetime->diff($value, false)->format('%r%y');
        }

        $value = new static($value);
        if ($value->isNull()) {
            return;
        }

        return $value->diffInYears($this->datetime) * -1;
    }

    /**
     * Difference in months
     *
     * @param  mixed    $value
     * @return int|null
     */
    public function diffInMonths($value)
    {
        if ($this->isNull()) {
            return;
        }

        if ($value instanceof DateTime) {
            return (int) $this->diffInYears($value) * 12 + $this->datetime->diff($value, false)->format('%r%m');
        }

        $value = new static($value);
        if ($value->isNull()) {
            return;
        }

        return $value->diffInMonths($this->datetime) * -1;
    }

    /**
     * Difference in days
     *
     * @param  mixed    $value
     * @return int|null
     */
    public function diffInDays($value)
    {
        $seconds = $this->diffInSeconds($value);
        if ($seconds !== null) {
            return (int) floor($seconds / 60 / 60 / 24);
        }
    }

    /**
     * Difference in hours
     *
     * @param  mixed    $value
     * @return int|null
     */
    public function diffInHours($value)
    {
        $seconds = $this->diffInSeconds($value);
        if ($seconds !== null) {
            return (int) floor($seconds / 60 / 60);
        }
    }

    /**
     * Difference in minutes
     *
     * @param  mixed    $value
     * @return int|null
     */
    public function diffInMinutes($value)
    {
        $seconds = $this->diffInSeconds($value);
        if ($seconds !== null) {
            return (int) floor($seconds / 60);
        }
    }

    /**
     * Difference in seconds
     *
     * @param  mixed    $value
     * @return int|null
     */
    public function diffInSeconds($value)
    {
        if ($this->isNull()) {
            return;
        }

        if ($value instanceof DateTime) {
            return (int) $this->datetime->diff($value, false)->format('%r%s');
        }

        $value = new static($value);
        if ($value->isNull()) {
            return;
        }

        return $value->getTimestamp() - $this->getTimestamp();
    }

    /**
     * Difference in microseconds
     *
     * @param  mixed    $value
     * @return int|null
     */
    public function diffInMicroseconds($value)
    {
        if ($this->isNull()) {
            return;
        }

        $value = new static($value);
        if ($value->isNull()) {
            return;
        }

        return $this->diffInSeconds($value) * 1000000 + $value->getMicrosecond() - $this->getMicrosecond();
    }

    /**
     * Is this equal with the given data?
     *
     * @param  mixed $value
     * @return bool
     */
    public function eq($value)
    {
        if ($this->isNotNull() && isset ($value)) {
            if ( ! $value instanceof static) {
                $value = new static($value);
            }

            if ($this->format('Y-m-d H:i:s.u') === $value->format('Y-m-d H:i:s.u')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Is this not equal with the given data?
     *
     * @param  mixed $value
     * @return bool
     */
    public function ne($value)
    {
        return ! $this->eq($value);
    }

    /**
     * Is this greater than the given data?
     *
     * @param  mixed $value
     * @return bool
     */
    public function gt($value)
    {
        if ($this->isNotNull() && isset ($value)) {
            if ( ! $value instanceof static) {
                $value = new static($value);
            }

            if ($this->diffInMicroseconds($value) < 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Is this greater than or equal with the given data?
     *
     * @param  mixed $value
     * @return bool
     */
    public function gte($value)
    {
        if ($this->isNotNull() && isset ($value)) {
            if ( ! $value instanceof static) {
                $value = new static($value);
            }

            if ($this->diffInMicroseconds($value) <= 0) {
                return true;
            }
        }

        return false;

        return false;
    }

    /**
     * Is this less than the given data?
     *
     * @param  mixed $value
     * @return bool
     */
    public function lt($value)
    {
        if ($this->isNotNull() && isset ($value)) {
            if ( ! $value instanceof static) {
                $value = new static($value);
            }

            if ($this->diffInMicroseconds($value) > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Is this less than or eqauel with the given data?
     *
     * @param  mixed $value
     * @return bool
     */
    public function lte($value)
    {
        if ($this->isNotNull() && isset ($value)) {
            if ( ! $value instanceof static) {
                $value = new static($value);
            }

            if ($this->diffInMicroseconds($value) >= 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Is this between or equal with the given data?
     *
     * @param  mixed $a
     * @param  mixed $b
     * @return bool
     */
    public function betweenEqual($a, $b)
    {
        if ($this->isNotNull() && isset ($a) && isset ($b)) {
            if ( ! $a instanceof static) {
                $a = new static($a);
            }

            if ( ! $b instanceof static) {
                $b = new static($b);
            }

            if ($a->lte($b)) {
                if ($this->gte($a) && $this->lte($b)) {
                    return true;
                }
            } elseif ($a->gte($b)) {
                if ($this->gte($b) && $this->lte($a)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Is this between the given data?
     *
     * @param  mixed $a
     * @param  mixed $b
     * @return bool
     */
    public function betweenNotEqual($a, $b)
    {
        if ($this->isNotNull() && isset ($a) && isset ($b)) {
            if ( ! $a instanceof static) {
                $a = new static($a);
            }

            if ( ! $b instanceof static) {
                $b = new static($b);
            }

            if ($a->lt($b)) {
                if ($this->gt($a) && $this->lt($b)) {
                    return true;
                }
            } elseif ($a->gt($b)) {
                if ($this->gt($b) && $this->lt($a)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * True if it's a leap year
     *
     * @param  int  $year
     * @return bool
     */
    public static function checkLeapYear($year)
    {
        $year = Cast::Int($year);

        if ($year === 0) {
            return true;
        } elseif ($year % 4 !== 0) {
            return false;
        } elseif ($year % 100 !== 0) {
            return true;
        } elseif ($year % 400 !== 0) {
            return false;
        }

        return true;
    }

    /**
     * True if the timezone is deprecated
     * http://php.net/manual/en/timezones.others.php
     *
     * @param  string|DateTimeZone
     * @return bool
     */
    public static function isTimezoneDeprecated($timezone)
    {
        if ($timezone instanceof DateTimeZone) {
            $timezone = $timezone->getName();
        }

        foreach (timezone_identifiers_list() as $key => $value) {
            if ($timezone === $value) {
                return false;
            }
        }

        return true;
    }
}
