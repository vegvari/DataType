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
     * @cover ::__construct
     */
    public function construct()
    {
        $instance = new DateTimeType();
        $this->assertSame(date_default_timezone_get(), $instance->getTimeZone()->getName());

        $instance = new DateTimeType(null, 'UTC');
        $this->assertSame('UTC', $instance->getTimeZone()->getName());

        $instance = new DateTimeType(null, new DateTimeZone('Europe/London'));
        $this->assertSame('Europe/London', $instance->getTimeZone()->getName());

        $this->setExpectedException('InvalidArgumentException');
        $instance = new DateTimeType(null, 'GMT');
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
    public function setDateTimeFail(array $data)
    {
        $this->setExpectedException('InvalidArgumentException');
        $instance = new DateTimeType();
        $instance->setDateTime($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);
    }

    public function setDateTimeFailProvider()
    {
        return [
            // year
            [['a', 1, 1, 0, 0, 0, 0]],
            // month
            [[0, 0, 1, 0, 0, 0, 0]],
            [[0, 13, 1, 0, 0, 0, 0]],
            // day
            [[0, 1, 0, 0, 0, 0, 0]],
            [[0, 1, 32, 0, 0, 0, 0]],
            // hour
            [[0, 1, 1, -1, 0, 0, 0]],
            [[0, 1, 1, 24, 0, 0, 0]],
            // minute
            [[0, 1, 1, 0, -1, 0, 0]],
            [[0, 1, 1, 0, 60, 0, 0]],
            // second
            [[0, 1, 1, 0, 0, -1, 0]],
            [[0, 1, 1, 0, 0, 60, 0]],
            // microsecond
            [[0, 1, 1, 0, 0, 0, -1]],
            [[0, 1, 1, 0, 0, 0, 1000000]],
            // no leap year
            [[1, 2, 29, 0, 0, 0, 0]],
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
    }
}
