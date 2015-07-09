<?php

namespace Data\Type;

use InvalidArgumentException;

class TimeType extends Type
{
    /**
     * @var int
     */
    const posix = -62167219200;

    /**
     * @var int
     */
    protected $year = 0;

    /**
     * @var int
     */
    protected $month = 1;

    /**
     * @var int
     */
    protected $day = 1;

    /**
     * @var int
     */
    protected $hour = 0;

    /**
     * @var int
     */
    protected $minute = 0;

    /**
     * @var int
     */
    protected $second = 0;

    /**
     * @var bool
     */
    protected $leap_year = true;

    /**
     * @var int
     */
    protected $posix = -62167219200;

    /**
     * @var string
     */
    protected $format;

    /**
     * Constructor
     *
     * @param mixed  $value
     * @param string $format
     */
    public function __construct($value = null, $format = 'Y-m-d H:i:s')
    {
        $this->setFormat($format);
        $this->set($value);
        $this->calculate();
    }

    /**
     * Set the format
     *
     * @param  string $format
     */
    protected function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * Set year
     *
     * @param int $year
     */
    public function setYear($year)
    {
        $this->addYear($year - $this->year);
    }

    /**
     * Set month
     *
     * @param int $month 1-12
     */
    public function setMonth($month)
    {
        if ($month < 1 || $month > 12) {
            throw new InvalidArgumentException('Invalid month: "' . $month . '"');
        }
        $month = (int) $month;

        $this->addMonth($month - $this->month);
    }

    /**
     * Set day
     *
     * @param int $day 1-31
     */
    public function setDay($day)
    {
        if ($day >= 1 && $day <= 31) {
            if ($day < $this->day) {
                $this->subDay($this->day - $day);
            } elseif ($day > $this->day) {
                $this->addDay($day - $this->day);
            }
        }
    }

    /**
     * Add year
     *
     * @param int $year
     */
    public function addYear($year)
    {
        $from = $this->year;
        $to = $this->year + $year;

        if ($year < 0) {
            $from = $to;
            $to = $this->year;
        }

        $days = 0;
        for ($i = $from; $i < $to; $i++) {
            $days += 365;

            if ($this->month < 3) {
                if (self::checkLeapYear($i)) {
                    $days += 1;
                }
            } else {
                if (self::checkLeapYear($i + 1)) {
                    $days += 1;
                }
            }
        }

        if ($year > 0) {
            $this->addDay($days);
        } else {
            $this->subDay($days);
        }
    }

    /**
     * Add month
     *
     * @param int $month
     */
    public function addMonth($month)
    {
        $value = $this->value();

        if ($month >= 12) {
            $year = floor($month / 12);
            $month -= $year * 12;
            $this->addYear($year);
        }

        if ($month > 0) {
            $d = 0;
            $y = $this->year;
            for ($i = $this->month; $i < $this->month + $month; $i++) {
                $m = $i;
                if ($m > 12) {
                    $m = $m - 12;
                }

                if ($m === 12) {
                    $y++;
                }

                $d += 31;
                if (in_array($m, [2, 4, 6, 9, 11])) {
                    $d -= 1;

                    if ($m === 2) {
                        $d -= 2;

                        if (self::checkLeapYear($y)) {
                            $d += 1;
                        }
                    }
                }
            }

            $this->addDay($d);
        }

        if ($value !== $this->value()) {
            $this->notify();
        }
    }

    /**
     * Subtract month
     *
     * @param int $month
     */
    public function subMonth($month)
    {
        $value = $this->value();

        if ($month >= 12) {
            $year = floor($month / 12);
            $month -= $year * 12;
            $this->subYear($year);
        }

        if ($month > 0) {
            $d = 0;
            $y = $this->year;
            for ($i = $this->month; $i > $this->month - $month; $i--) {
                $m = $i - 1;
                if ($m < 1) {
                    $m = $m + 12;
                }

                if ($m === 12) {
                    $y--;
                }

                $d += 31;
                if (in_array($m, [2, 4, 6, 9, 11])) {
                    $d -= 1;

                    if ($m === 2) {
                        $d -= 2;

                        if (self::checkLeapYear($y)) {
                            $d += 1;
                        }
                    }
                }
            }

            $this->subDay($d);
        }

        if ($value !== $this->value()) {
            $this->notify();
        }
    }

    /**
     * Add day
     *
     * @param int $day
     */
    public function addDay($day)
    {
        $this->addSecond(60 * 60 * 24 * $day);
    }

    /**
     * Add hour
     *
     * @param int $hour
     */
    public function addHour($hour)
    {
        $this->addSecond(60 * 60 * $hour);
    }

    /**
     * Add minute
     *
     * @param int $minute
     */
    public function addMinute($minute)
    {
        $this->addSecond(60 * $minute);
    }

    /**
     * Add second
     *
     * @param int $second
     */
    public function addSecond($second)
    {
        $this->posix = (int) ($this->posix + $second);
        $this->calculate();
    }

    /**
     * Subtract year
     *
     * @param int $year
     */
    public function subYear($year)
    {
        $this->addYear($year * -1);
    }

    /**
     * Subtract day
     *
     * @param int $day
     */
    public function subDay($day)
    {
        $this->addDay($day * -1);
    }

    /**
     * Subtract hour
     *
     * @param int $hour
     */
    public function subHour($hour)
    {
        $this->addHour($hour * -1);
    }

    /**
     * Subtract minute
     *
     * @param int $minute
     */
    public function subMinute($minute)
    {
        $this->addMinute($minute * -1);
    }

    /**
     * Subtract second
     *
     * @param int $second
     */
    public function subSecond($second)
    {
        $this->addSecond($second * -1);
    }

    /**
     * Calculate properties
     */
    protected function calculate()
    {
        $this->year      = (int) date('Y', $this->posix);
        $this->month     = (int) date('m', $this->posix);
        $this->day       = (int) date('d', $this->posix);
        $this->hour      = (int) date('H', $this->posix);
        $this->minute    = (int) date('i', $this->posix);
        $this->second    = (int) date('s', $this->posix);
        $this->leap_year = (bool) date('L', $this->posix);

        $this->value = date($this->format, $this->posix);
    }

    /**
     * Check the value
     *
     * @param  mixed       $value
     * @return string|null
     */
    protected function check($value)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof StringType) {
            $value = $value->value();
        } else {
            if (is_array($value)) {
                throw new InvalidArgumentException('Invalid time, array given');
            }

            if (is_resource($value)) {
                throw new InvalidArgumentException('Invalid time, resource given');
            }

            if (is_object($value)) {
                throw new InvalidArgumentException('Invalid time, object given');
            }
        }

        if (preg_match('/^(?P<year>[0-9]{4,4})-(?P<month>[0-9]{2,2})-(?P<day>[0-9]{2,2}) (?P<hour>[0-9]{2,2}):(?P<minute>[0-9]{2,2}):(?P<second>[0-9]{2,2})$/ui', $value, $m)) {
            // month
            if ($m['month'] < 1 || $m['month'] > 12) {
                throw new InvalidArgumentException('Invalid month: "' . $m['month'] . '"');
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

            if ($m['day'] < 1 || $m['day'] > $day_max) {
                throw new InvalidArgumentException('Invalid day: "' . $m['day'] . '"');
            }

            // hour
            if ($m['hour'] < 0 || $m['hour'] > 23) {
                throw new InvalidArgumentException('Invalid hour: "' . $m['hour'] . '"');
            }

            // minute
            if ($m['minute'] < 0 || $m['minute'] > 59) {
                throw new InvalidArgumentException('Invalid minute: "' . $m['minute'] . '"');
            }

            // second
            if ($m['second'] < 0 || $m['second'] > 59) {
                throw new InvalidArgumentException('Invalid second: "' . $m['second'] . '"');
            }

            return $value;
        }

        throw new InvalidArgumentException('Invalid time: "' . $value . '"');
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
        if ($year === 0) {
            return true;
        } elseif ($year % 4 !== 0) {
            return false;
        } elseif ($year % 100 !== 0) {
            return true;
        } elseif ($year % 400 !== 0) {
            return false;
        } elseif ($year === 0) {
            return false;
        }

        return true;
    }
}
