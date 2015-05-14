<?php

namespace Data\Type;

use InvalidArgumentException;

class TimeType extends Type
{
    /**
     * Check the value
     *
     * @param  mixed  $value
     * @return string
     */
    public function check($value)
    {
        if (preg_match('/^(?P<year>[0-9]{4,4})-(?P<month>[0-9]{2,2})-(?P<day>[0-9]{2,2}) (?P<hour>[0-9]{2,2}):(?P<minute>[0-9]{2,2}):(?P<second>[0-9]{2,2})$/ui', $value, $m)) {
            // month
            if ($m['month'] < 1 && $m['month'] > 12) {
                throw new InvalidArgumentException('Invalid month: ' . $m['month']);
            }

            // day
            $day_max = 31;
            if ($m['month'] === '04' || $m['month'] === '06' || $m['month'] === '09' || $m['month'] === '11') {

            } elseif ($m['month'] === '02') {
                $day_max = 28;
                if (self::checkLeapYear($m['year'])) {
                    $day_max = 29;
                }
            }

            if ($m['day'] < 1 && $m['day'] > $day_max) {
                throw new InvalidArgumentException('Invalid day: ' . $m['day']);
            }

            // hour
            if ($m['hour'] < 0 && $m['hour'] > 23) {
                throw new InvalidArgumentException('Invalid hour: ' . $m['hour']);
            }

            // minute
            if ($m['minute'] < 0 && $m['minute'] > 59) {
                throw new InvalidArgumentException('Invalid minute: ' . $m['minute']);
            }

            // second
            if ($m['second'] < 0 && $m['second'] > 59) {
                throw new InvalidArgumentException('Invalid second: ' . $m['second']);
            }
        } else {
            throw new InvalidArgumentException('Invalid format: ' . $value);
        }

        return $value;
    }

    /**
     * Returns the year
     *
     * @return int|null
     */
    public function getYear()
    {
        if ($this->value !== null) {
            preg_match('/^(?P<year>[0-9]{4,4})-(?P<month>[0-9]{2,2})-(?P<day>[0-9]{2,2}) (?P<hour>[0-9]{2,2}):(?P<minute>[0-9]{2,2}):(?P<second>[0-9]{2,2})$/ui', $this->value, $m);
            return (int) $m['year'];
        }
    }

    /**
     * Returns the month
     *
     * @return int|null
     */
    public function getMonth()
    {
        if ($this->value !== null) {
            preg_match('/^(?P<year>[0-9]{4,4})-(?P<month>[0-9]{2,2})-(?P<day>[0-9]{2,2}) (?P<hour>[0-9]{2,2}):(?P<minute>[0-9]{2,2}):(?P<second>[0-9]{2,2})$/ui', $this->value, $m);
            return (int) $m['month'];
        }
    }

    /**
     * Returns the day
     *
     * @return int|null
     */
    public function getDay()
    {
        if ($this->value !== null) {
            preg_match('/^(?P<year>[0-9]{4,4})-(?P<month>[0-9]{2,2})-(?P<day>[0-9]{2,2}) (?P<hour>[0-9]{2,2}):(?P<minute>[0-9]{2,2}):(?P<second>[0-9]{2,2})$/ui', $this->value, $m);
            return (int) $m['day'];
        }
    }

    /**
     * Returns the hour
     *
     * @return int|null
     */
    public function getHour()
    {
        if ($this->value !== null) {
            preg_match('/^(?P<year>[0-9]{4,4})-(?P<month>[0-9]{2,2})-(?P<day>[0-9]{2,2}) (?P<hour>[0-9]{2,2}):(?P<minute>[0-9]{2,2}):(?P<second>[0-9]{2,2})$/ui', $this->value, $m);
            return (int) $m['hour'];
        }
    }

    /**
     * Returns the minute
     *
     * @return int|null
     */
    public function getMinute()
    {
        if ($this->value !== null) {
            preg_match('/^(?P<year>[0-9]{4,4})-(?P<month>[0-9]{2,2})-(?P<day>[0-9]{2,2}) (?P<hour>[0-9]{2,2}):(?P<minute>[0-9]{2,2}):(?P<second>[0-9]{2,2})$/ui', $this->value, $m);
            return (int) $m['minute'];
        }
    }

    /**
     * Returns the second
     *
     * @return int|null
     */
    public function getSecond()
    {
        if ($this->value !== null) {
            preg_match('/^(?P<year>[0-9]{4,4})-(?P<month>[0-9]{2,2})-(?P<day>[0-9]{2,2}) (?P<hour>[0-9]{2,2}):(?P<minute>[0-9]{2,2}):(?P<second>[0-9]{2,2})$/ui', $this->value, $m);
            return (int) $m['second'];
        }
    }

    /**
     * Returns the value in unix timestamp
     *
     * @return int|null
     */
    public function getUnixTimestamp()
    {
        if ($this->value !== null) {
            return strtotime($this->value);
        }
    }

    /**
     * True if it's a leap year
     *
     * @return bool
     */
    public function isLeapYear()
    {
        return self::checkLeapYear($this->getYear());
    }

    /**
     * True if it's a leap year
     *
     * @param  int  $year
     * @return bool
     */
    public static function checkLeapYear($year)
    {
        $year = Cast::_Int($year);

        if ($year % 4 !== 0) {
            return false;
        } elseif ($year % 100 !== 0) {
            return true;
        } elseif ($year % 400 !== 0) {
            return false;
        } elseif ($year !== 0) {
            return true;
        }

        return false;
    }
}
