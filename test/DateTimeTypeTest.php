<?php

use Data\Type\Cast;
use Data\Type\IntType;
use Data\Type\BoolType;
use Data\Type\FloatType;
use Data\Type\StringType;
use Data\Type\DateTimeType;

/**
 * @coversDefaultClass \Data\Type\DateTimeType
 */
class DateTimeTypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers ::__toString
     */
    public function toString()
    {
        $instance = new DateTimeType();
        $this->assertSame('', (string) $instance);
    }

    /**
     * @test
     * @covers ::__construct
     * #covers ::check
     */
    public function construct()
    {
        $instance = new DateTimeType();
        $this->assertSame(date_default_timezone_get(), $instance->getTimeZone()->getName());

        $instance = new DateTimeType(null, 'UTC');
        $this->assertSame('UTC', $instance->getTimeZone()->getName());

        $instance = new DateTimeType(null, new DateTimeZone('Europe/London'));
        $this->assertSame('Europe/London', $instance->getTimeZone()->getName());

        $instance = new DateTimeType(new DateTimeType('2012-01-01'));
        $this->assertSame(2012, $instance->getYear());
        $this->assertSame(1, $instance->getMonth());
        $this->assertSame(1, $instance->getDay());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::check
     */
    public function constructFail()
    {
        $this->setExpectedException('\Data\Type\Exceptions\InvalidTimeZoneException');
        $instance = new DateTimeType(null, 'GMT');
    }

    /**
     * @test
     * @covers ::check
     */
    public function check()
    {
        $instance = new DateTimeType();
        $this->assertSame(null, $instance->value());

        $instance = new DateTimeType('');
        $this->assertSame(null, $instance->value());

        $instance = new DateTimeType(new BoolType());
        $this->assertSame(null, $instance->value());

        $instance = new DateTimeType(new FloatType());
        $this->assertSame(null, $instance->value());

        $instance = new DateTimeType(new IntType());
        $this->assertSame(null, $instance->value());

        $instance = new DateTimeType(new StringType());
        $this->assertSame(null, $instance->value());

        $instance = new DateTimeType(new DateTimeType());
        $this->assertSame(null, $instance->value());

        $instance = new DateTimeType(new StringType('2012-01-01'));
        $this->assertSame('2012-01-01 00:00:00', $instance->value());

        $instance = new DateTimeType(new DateTimeType('2012-01-01'));
        $this->assertSame('2012-01-01 00:00:00', $instance->value());

        $instance = new DateTimeType('now');
        $this->assertLessThanOrEqual(1, abs($instance->diffInSeconds('now')));
    }

    /**
     * @test
     * @covers ::check
     */
    public function checkFail()
    {
        $this->setExpectedException('\Data\Type\Exceptions\InvalidDateTimeException');
        $instance = new DateTimeType('ASDFASDFASDF');
    }

    /**
     * @test
     * @dataProvider setDateTimeProvider
     * @covers       ::setDateTime
     * @covers       ::setDate
     * @covers       ::setTime
     * @covers       ::setYear
     * @covers       ::setMonth
     * @covers       ::setDay
     * @covers       ::setHour
     * @covers       ::setMinute
     * @covers       ::setSecond
     * @covers       ::setMicrosecond
     * @covers       ::check
     * @covers       ::getTimezone
     * @covers       ::getDateTime
     * @covers       ::getYear
     * @covers       ::getMonth
     * @covers       ::getDay
     * @covers       ::getHour
     * @covers       ::getMinute
     * @covers       ::getSecond
     * @covers       ::getMicrosecond
     * @covers       ::getTimestamp
     * @covers       ::format
     */
    public function setDateTime(array $data, array $expected)
    {
        $instance = new DateTimeType();
        $this->assertInstanceOf('DateTimeZone', $instance->getTimeZone());
        $this->assertSame(null, $instance->getDateTime());
        $this->assertSame(null, $instance->getYear());
        $this->assertSame(null, $instance->getMonth());
        $this->assertSame(null, $instance->getDay());
        $this->assertSame(null, $instance->getHour());
        $this->assertSame(null, $instance->getMinute());
        $this->assertSame(null, $instance->getSecond());
        $this->assertSame(null, $instance->getMicrosecond());
        $this->assertSame(null, $instance->getTimestamp());
        $this->assertSame(null, $instance->format('Y-m-d H:i:s.u'));

        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->setDateTime($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]));
        $this->assertInstanceOf('DateTime', $instance->getDateTime());
        $this->assertSame($expected[7], $instance->format('Y-m-d H:i:s.u'));
        $this->assertSame($expected[8], $instance->getTimestamp());

        $instance = new DateTimeType();
        $instance->setDate($data[0], $data[1], $data[2]);
        $this->assertSame($expected[0], $instance->getYear());
        $this->assertSame($expected[1], $instance->getMonth());
        $this->assertSame($expected[2], $instance->getDay());

        $instance = new DateTimeType();
        $instance->setTime($data[3], $data[4], $data[5], $data[6]);
        $this->assertSame($expected[3], $instance->getHour());
        $this->assertSame($expected[4], $instance->getMinute());
        $this->assertSame($expected[5], $instance->getSecond());
        $this->assertSame($expected[6], $instance->getMicrosecond());

        $instance = new DateTimeType();
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->setYear($data[0]));
        $this->assertSame($expected[0], $instance->getYear());

        $instance = new DateTimeType();
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->setMonth($data[1]));
        $this->assertSame($expected[1], $instance->getMonth());

        $instance = new DateTimeType();
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->setDay($data[2]));
        $this->assertSame($expected[2], $instance->getDay());

        $instance = new DateTimeType();
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->setHour($data[3]));
        $this->assertSame($expected[3], $instance->getHour());

        $instance = new DateTimeType();
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->setMinute($data[4]));
        $this->assertSame($expected[4], $instance->getMinute());

        $instance = new DateTimeType();
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->setSecond($data[5]));
        $this->assertSame($expected[5], $instance->getSecond());

        $instance = new DateTimeType();
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->setMicrosecond($data[6]));
        $this->assertSame($expected[6], $instance->getMicrosecond());
    }

    public function setDateTimeProvider()
    {
        return [
            [
                [null, null, null, null, null, null, null],
                [0, 1, 1, 0, 0, 0, 0, '0000-01-01 00:00:00.000000', -62167219200]
            ],
            [
                [1, 2, 3, 4, 5, 6, 7],
                [1, 2, 3, 4, 5, 6, 7, '0001-02-03 04:05:06.000007', -62132730894]
            ],
            [
                [9999, 12, 31, 23, 59, 59, 999999],
                [9999, 12, 31, 23, 59, 59, 999999, '9999-12-31 23:59:59.999999', 253402300799]
            ],
            [
                [0, 2, 29, 0, 0, 0, 0],
                [0, 2, 29, 0, 0, 0, 0, '0000-02-29 00:00:00.000000', -62162121600]
            ],
            [
                [1970, 1, 1, 0, 0, 0, 0],
                [1970, 1, 1, 0, 0, 0, 0, '1970-01-01 00:00:00.000000', 0]
            ],
        ];
    }

    /**
     * @test
     * @covers ::setDateTime
     */
    public function setDateTimeExistingValue()
    {
        $instance = new DateTimeType();
        $instance->setDateTime(2010, 11, 12, 13, 14, 15, 16);
        $instance->setDateTime(null, null, null, null, null, null, null);

        $this->assertSame(2010, $instance->getYear());
        $this->assertSame(11, $instance->getMonth());
        $this->assertSame(12, $instance->getDay());
        $this->assertSame(13, $instance->getHour());
        $this->assertSame(14, $instance->getMinute());
        $this->assertSame(15, $instance->getSecond());
        $this->assertSame(16, $instance->getMicrosecond());

        $instance->setDateTime(0, null, null, null, null, null, null);
        $this->assertSame(0, $instance->getYear());
        $this->assertSame(11, $instance->getMonth());
        $this->assertSame(12, $instance->getDay());
        $this->assertSame(13, $instance->getHour());
        $this->assertSame(14, $instance->getMinute());
        $this->assertSame(15, $instance->getSecond());
        $this->assertSame(16, $instance->getMicrosecond());

        $instance->setDateTime(0, 1, null, null, null, null, null);
        $this->assertSame(0, $instance->getYear());
        $this->assertSame(1, $instance->getMonth());
        $this->assertSame(12, $instance->getDay());
        $this->assertSame(13, $instance->getHour());
        $this->assertSame(14, $instance->getMinute());
        $this->assertSame(15, $instance->getSecond());
        $this->assertSame(16, $instance->getMicrosecond());

        $instance->setDateTime(0, 1, 2, null, null, null, null);
        $this->assertSame(0, $instance->getYear());
        $this->assertSame(1, $instance->getMonth());
        $this->assertSame(2, $instance->getDay());
        $this->assertSame(13, $instance->getHour());
        $this->assertSame(14, $instance->getMinute());
        $this->assertSame(15, $instance->getSecond());
        $this->assertSame(16, $instance->getMicrosecond());

        $instance->setDateTime(0, 1, 2, 3, null, null, null);
        $this->assertSame(0, $instance->getYear());
        $this->assertSame(1, $instance->getMonth());
        $this->assertSame(2, $instance->getDay());
        $this->assertSame(3, $instance->getHour());
        $this->assertSame(14, $instance->getMinute());
        $this->assertSame(15, $instance->getSecond());
        $this->assertSame(16, $instance->getMicrosecond());

        $instance->setDateTime(0, 1, 2, 3, 4, null, null);
        $this->assertSame(0, $instance->getYear());
        $this->assertSame(1, $instance->getMonth());
        $this->assertSame(2, $instance->getDay());
        $this->assertSame(3, $instance->getHour());
        $this->assertSame(4, $instance->getMinute());
        $this->assertSame(15, $instance->getSecond());
        $this->assertSame(16, $instance->getMicrosecond());

        $instance->setDateTime(0, 1, 2, 3, 4, 5, null);
        $this->assertSame(0, $instance->getYear());
        $this->assertSame(1, $instance->getMonth());
        $this->assertSame(2, $instance->getDay());
        $this->assertSame(3, $instance->getHour());
        $this->assertSame(4, $instance->getMinute());
        $this->assertSame(5, $instance->getSecond());
        $this->assertSame(16, $instance->getMicrosecond());

        $instance->setDateTime(0, 1, 2, 3, 4, 5, 6);
        $this->assertSame(0, $instance->getYear());
        $this->assertSame(1, $instance->getMonth());
        $this->assertSame(2, $instance->getDay());
        $this->assertSame(3, $instance->getHour());
        $this->assertSame(4, $instance->getMinute());
        $this->assertSame(5, $instance->getSecond());
        $this->assertSame(6, $instance->getMicrosecond());
    }

    /**
     * @test
     * @dataProvider setDateTimeFailProvider
     * @covers       ::setDateTime
     */
    public function setDateTimeFail(array $data, $exception)
    {
        $this->setExpectedException($exception);
        $instance = new DateTimeType();
        $instance->setDateTime($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);
    }

    public function setDateTimeFailProvider()
    {
        return [
            // year
            [['a', 1, 1, 0, 0, 0, 0], '\Data\Type\Exceptions\InvalidYearException'],
            // month
            [[0, 0, 1, 0, 0, 0, 0], '\Data\Type\Exceptions\InvalidMonthException'],
            [[0, 13, 1, 0, 0, 0, 0], '\Data\Type\Exceptions\InvalidMonthException'],
            // day
            [[0, 1, 0, 0, 0, 0, 0], '\Data\Type\Exceptions\InvalidDayException'],
            [[0, 1, 32, 0, 0, 0, 0], '\Data\Type\Exceptions\InvalidDayException'],
            // hour
            [[0, 1, 1, -1, 0, 0, 0], '\Data\Type\Exceptions\InvalidHourException'],
            [[0, 1, 1, 24, 0, 0, 0], '\Data\Type\Exceptions\InvalidHourException'],
            // minute
            [[0, 1, 1, 0, -1, 0, 0], '\Data\Type\Exceptions\InvalidMinuteException'],
            [[0, 1, 1, 0, 60, 0, 0], '\Data\Type\Exceptions\InvalidMinuteException'],
            // second
            [[0, 1, 1, 0, 0, -1, 0], '\Data\Type\Exceptions\InvalidSecondException'],
            [[0, 1, 1, 0, 0, 60, 0], '\Data\Type\Exceptions\InvalidSecondException'],
            // microsecond
            [[0, 1, 1, 0, 0, 0, -1], '\Data\Type\Exceptions\InvalidMicrosecondException'],
            [[0, 1, 1, 0, 0, 0, 1000000], '\Data\Type\Exceptions\InvalidMicrosecondException'],
            // no leap year
            [[1, 2, 29, 0, 0, 0, 0], '\Data\Type\Exceptions\InvalidDateException'],
        ];
    }

    /**
     * @test
     * @covers ::addYear
     * @covers ::addMonth
     * @covers ::addDay
     * @covers ::addHour
     * @covers ::addMinute
     * @covers ::addSecond
     * @covers ::addMicrosecond
     * @covers ::subYear
     * @covers ::subMonth
     * @covers ::subDay
     * @covers ::subHour
     * @covers ::subMinute
     * @covers ::subSecond
     * @covers ::subMicrosecond
     */
    public function addSub()
    {
        $instance = new DateTimeType();
        $instance->addYear(1);
        $instance->addMonth(1);
        $instance->addDay(1);
        $instance->addHour(1);
        $instance->addMinute(1);
        $instance->addSecond(1);
        $instance->addMicrosecond(1);
        $this->assertSame(null, $instance->getYear());
        $this->assertSame(null, $instance->getMonth());
        $this->assertSame(null, $instance->getDay());
        $this->assertSame(null, $instance->getHour());
        $this->assertSame(null, $instance->getMinute());
        $this->assertSame(null, $instance->getSecond());
        $this->assertSame(null, $instance->getMicrosecond());

        $instance->setDateTime(null, null, null, null, null, null, null);
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->addYear(1));
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->addMonth(1));
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->addDay(1));
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->addHour(1));
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->addMinute(1));
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->addSecond(1));
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->addMicrosecond(1));
        $this->assertSame(1, $instance->getYear());
        $this->assertSame(2, $instance->getMonth());
        $this->assertSame(2, $instance->getDay());
        $this->assertSame(1, $instance->getHour());
        $this->assertSame(1, $instance->getMinute());
        $this->assertSame(1, $instance->getSecond());
        $this->assertSame(1, $instance->getMicrosecond());
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->subYear(1));
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->subMonth(1));
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->subDay(1));
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->subHour(1));
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->subMinute(1));
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->subSecond(1));
        $this->assertInstanceOf('\Data\Type\DateTimeType', $instance->subMicrosecond(1));
        $this->assertSame(0, $instance->getYear());
        $this->assertSame(1, $instance->getMonth());
        $this->assertSame(1, $instance->getDay());
        $this->assertSame(0, $instance->getHour());
        $this->assertSame(0, $instance->getMinute());
        $this->assertSame(0, $instance->getSecond());
        $this->assertSame(0, $instance->getMicrosecond());

        $instance->addMicrosecond(2000001);
        $this->assertSame(2, $instance->getSecond());
        $this->assertSame(1, $instance->getMicrosecond());

        $instance->subMicrosecond(1000002);
        $this->assertSame(0, $instance->getSecond());
        $this->assertSame(999999, $instance->getMicrosecond());
    }

    /**
     * @test
     * @covers ::getDayOfWeek
     */
    public function dayOfWeek()
    {
        $instance = new DateTimeType();
        $this->assertSame(null, $instance->getDayOfWeek());

        $instance = new DateTimeType('2013-04-01');
        $this->assertSame(1, $instance->getDayOfWeek());

        $instance->setDay(2);
        $this->assertSame(2, $instance->getDayOfWeek());

        $instance->setDay(3);
        $this->assertSame(3, $instance->getDayOfWeek());

        $instance->setDay(4);
        $this->assertSame(4, $instance->getDayOfWeek());

        $instance->setDay(5);
        $this->assertSame(5, $instance->getDayOfWeek());

        $instance->setDay(6);
        $this->assertSame(6, $instance->getDayOfWeek());

        $instance->setDay(7);
        $this->assertSame(7, $instance->getDayOfWeek());

        $instance->setDay(8);
        $this->assertSame(1, $instance->getDayOfWeek());
    }

    /**
     * @test
     * @covers ::diffInYears
     * @covers ::diffInMonths
     * @covers ::diffInDays
     * @covers ::diffInHours
     * @covers ::diffInMinutes
     * @covers ::diffInSeconds
     * @covers ::diffInMicroseconds
     */
    public function diff()
    {
        $instance = new DateTimeType();
        $instance2 = new DateTimeType();

        $this->assertSame(null, $instance->diffInYears($instance2));
        $this->assertSame(null, $instance->diffInMonths($instance2));
        $this->assertSame(null, $instance->diffInDays($instance2));
        $this->assertSame(null, $instance->diffInHours($instance2));
        $this->assertSame(null, $instance->diffInMinutes($instance2));
        $this->assertSame(null, $instance->diffInSeconds($instance2));
        $this->assertSame(null, $instance->diffInMicroSeconds($instance2));

        $this->assertSame(null, $instance->diffInYears(new DateTimeType()));
        $this->assertSame(null, $instance->diffInMonths(new DateTimeType()));
        $this->assertSame(null, $instance->diffInDays(new DateTimeType()));
        $this->assertSame(null, $instance->diffInHours(new DateTimeType()));
        $this->assertSame(null, $instance->diffInMinutes(new DateTimeType()));
        $this->assertSame(null, $instance->diffInSeconds(new DateTimeType()));
        $this->assertSame(null, $instance->diffInMicroSeconds(new DateTimeType()));

        $instance->setYear(2010);
        $instance2->setYear(2013);

        $this->assertSame(3, $instance->diffInYears('2013-01-01'));
        $this->assertSame(3, $instance->diffInYears($instance2));
        $this->assertSame(-3, $instance2->diffInYears($instance));

        $this->assertSame(36, $instance->diffInMonths('2013-01-01'));
        $this->assertSame(36, $instance->diffInMonths($instance2));
        $this->assertSame(-36, $instance2->diffInMonths($instance));

        $this->assertSame(1096, $instance->diffInDays('2013-01-01'));
        $this->assertSame(1096, $instance->diffInDays($instance2));
        $this->assertSame(-1096, $instance2->diffInDays($instance));

        $this->assertSame(26304, $instance->diffInHours('2013-01-01'));
        $this->assertSame(26304, $instance->diffInHours($instance2));
        $this->assertSame(-26304, $instance2->diffInHours($instance));

        $this->assertSame(1578240, $instance->diffInMinutes('2013-01-01'));
        $this->assertSame(1578240, $instance->diffInMinutes($instance2));
        $this->assertSame(-1578240, $instance2->diffInMinutes($instance));

        $this->assertSame(94694400, $instance->diffInSeconds('2013-01-01'));
        $this->assertSame(94694400, $instance->diffInSeconds($instance2));
        $this->assertSame(-94694400, $instance2->diffInSeconds($instance));

        $this->assertSame(94694400000000, $instance->diffInMicroSeconds('2013-01-01'));
        $this->assertSame(94694400000000, $instance->diffInMicroSeconds($instance2));
        $this->assertSame(-94694400000000, $instance2->diffInMicroSeconds($instance));

        $instance->setDate(2012, 2, 29);
        $this->assertSame(11, $instance->diffInMonths('2013-02-28'));
        $this->assertSame(365, $instance->diffInDays('2013-02-28'));

        $this->assertSame(12, $instance->diffInMonths('2013-03-01'));
        $this->assertSame(366, $instance->diffInDays('2013-03-01'));

        $instance->setDate(2012, 3, 01);
        $this->assertSame(12, $instance->diffInMonths('2013-03-01'));
        $this->assertSame(365, $instance->diffInDays('2013-03-01'));

        $instance = new DateTimeType();
        $instance->setDateTime(2012, 1, 1, 0, 0, 0, 0);
        $this->assertSame(10, $instance->diffInSeconds(new DateTime('2012-01-01 00:00:10')));

        $this->assertSame(null, $instance->diffInYears(new DateTimeType()));
        $this->assertSame(null, $instance->diffInMonths(new DateTimeType()));
        $this->assertSame(null, $instance->diffInDays(new DateTimeType()));
        $this->assertSame(null, $instance->diffInHours(new DateTimeType()));
        $this->assertSame(null, $instance->diffInMinutes(new DateTimeType()));
        $this->assertSame(null, $instance->diffInSeconds(new DateTimeType()));
        $this->assertSame(null, $instance->diffInMicroSeconds(new DateTimeType()));
    }

    /**
     * @test
     * @covers ::eq
     * @covers ::ne
     * @covers ::gt
     * @covers ::gte
     * @covers ::lt
     * @covers ::lte
     * @covers ::betweenEqual
     * @covers ::betweenNotEqual
     */
    public function compare()
    {
        $instance = new DateTimeType();
        $instance2 = new DateTimeType();
        $instance3 = new DateTimeType();

        $instance->setYear(2012);
        $instance2->setYear(2012);
        $instance3->setYear(2012);

        // equal
        $this->assertSame(true, $instance->eq($instance2));
        $this->assertSame(false, $instance->ne($instance2));
        $this->assertSame(false, $instance->gt($instance2));
        $this->assertSame(true, $instance->gte($instance2));
        $this->assertSame(false, $instance->lt($instance2));
        $this->assertSame(true, $instance->lte($instance2));

        $this->assertSame(true, $instance->eq('2012-01-01 00:00:00'));
        $this->assertSame(false, $instance->ne('2012-01-01 00:00:00'));
        $this->assertSame(false, $instance->gt('2012-01-01 00:00:00'));
        $this->assertSame(true, $instance->gte('2012-01-01 00:00:00'));
        $this->assertSame(false, $instance->lt('2012-01-01 00:00:00'));
        $this->assertSame(true, $instance->lte('2012-01-01 00:00:00'));

        $this->assertSame(true, $instance3->betweenEqual('2012-01-01', '2012-01-01'));
        $this->assertSame(false, $instance3->betweenNotEqual('2012-01-01', '2012-01-01'));
        $this->assertSame(true, $instance3->betweenNotEqual('2011-01-01', '2013-01-01'));
        $this->assertSame(true, $instance3->betweenNotEqual('2011-01-01', '2013-01-01'));
        $this->assertSame(true, $instance3->betweenNotEqual('2013-01-01', '2011-01-01'));
        $this->assertSame(true, $instance3->betweenNotEqual('2013-01-01', '2011-01-01'));
        $this->assertSame(false, $instance3->betweenNotEqual('2014-01-01', '2015-01-01'));
        $this->assertSame(false, $instance3->betweenNotEqual('2014-01-01', '2015-01-01'));

        // equal with microseconds
        $instance->setMicrosecond(1);
        $instance2->setMicrosecond(1);
        $instance3->setMicrosecond(1);
        $this->assertSame(true, $instance->eq($instance2));
        $this->assertSame(false, $instance->ne($instance2));
        $this->assertSame(false, $instance->gt($instance2));
        $this->assertSame(true, $instance->gte($instance2));
        $this->assertSame(false, $instance->lt($instance2));
        $this->assertSame(true, $instance->lte($instance2));
        $this->assertSame(true, $instance3->betweenEqual($instance, $instance2));
        $this->assertSame(false, $instance3->betweenNotEqual($instance, $instance2));

        // less
        $instance->setMicrosecond(1);
        $instance2->setMicrosecond(3);
        $instance3->setMicrosecond(2);
        $this->assertSame(false, $instance->eq($instance2));
        $this->assertSame(true, $instance->ne($instance2));
        $this->assertSame(false, $instance->gt($instance2));
        $this->assertSame(false, $instance->gte($instance2));
        $this->assertSame(true, $instance->lt($instance2));
        $this->assertSame(true, $instance->lte($instance2));
        $this->assertSame(true, $instance3->betweenEqual($instance, $instance2));
        $this->assertSame(true, $instance3->betweenNotEqual($instance, $instance2));

        // greater
        $instance->setMicrosecond(3);
        $instance2->setMicrosecond(1);
        $instance3->setMicrosecond(2);
        $this->assertSame(false, $instance->eq($instance2));
        $this->assertSame(true, $instance->ne($instance2));
        $this->assertSame(true, $instance->gt($instance2));
        $this->assertSame(true, $instance->gte($instance2));
        $this->assertSame(false, $instance->lt($instance2));
        $this->assertSame(false, $instance->lte($instance2));
        $this->assertSame(true, $instance3->betweenEqual($instance, $instance2));
        $this->assertSame(true, $instance3->betweenNotEqual($instance, $instance2));

        $instance3->setMicrosecond(4);
        $this->assertSame(false, $instance3->betweenEqual($instance, $instance2));
        $this->assertSame(false, $instance3->betweenNotEqual($instance, $instance2));
    }

    /**
     * @test
     * @covers ::checkLeapYear
     */
    public function checkLeapYear()
    {
        $this->assertSame(true, DateTimeType::checkLeapYear(0));
        $this->assertSame(true, DateTimeType::checkLeapYear(2000));
        $this->assertSame(true, DateTimeType::checkLeapYear(2012));
        $this->assertSame(false, DateTimeType::checkLeapYear(1));
        $this->assertSame(false, DateTimeType::checkLeapYear(1900));
    }

    /**
     * @test
     * @covers ::isTimezoneDeprecated
     */
    public function isTimezoneDeprecated()
    {
        $this->assertSame(true, DateTimeType::isTimezoneDeprecated('GMT'));
        $this->assertSame(true, DateTimeType::isTimezoneDeprecated(new DateTimeZone('GMT')));

        $this->assertSame(false, DateTimeType::isTimezoneDeprecated('UTC'));
        $this->assertSame(false, DateTimeType::isTimezoneDeprecated(new DateTimeZone('UTC')));
    }
}
