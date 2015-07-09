<?php

use Data\Type\Cast;
use Data\Type\IntType;
use Data\Type\BoolType;
use Data\Type\TimeType;
use Data\Type\FloatType;
use Data\Type\StringType;

/**
 * @coversDefaultClass \Data\Type\TimeType
 */
class TimeTypeTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 * @covers ::addYear
	 */
	public function addYear()
	{
		$instance = new TimeType();

		$instance->addYear(1);
		$this->assertSame('0001-01-01 00:00:00', $instance->value());

		$instance->addYear(9);
		$this->assertSame('0010-01-01 00:00:00', $instance->value());

		$instance->addYear(2002);
		$this->assertSame('2012-01-01 00:00:00', $instance->value());
	}

	/**
	 * @test
	 * @covers ::subYear
	 */
	public function subYear()
	{
		$instance = new TimeType();

		$instance->subYear(1);
		$this->assertSame('-0001-01-01 00:00:00', $instance->value());

		$instance->subYear(9);
		$this->assertSame('-0010-01-01 00:00:00', $instance->value());

		$instance->subYear(2002);
		$this->assertSame('-2012-01-01 00:00:00', $instance->value());
	}

	/**
	 * @test
	 * @covers ::addMonth
	 */
	public function addMonth()
	{
		$instance = new TimeType();

		$instance->addMonth(1);
		$this->assertSame('0000-02-01 00:00:00', $instance->value());

		$instance->addMonth(9);
		$this->assertSame('0000-11-01 00:00:00', $instance->value());

		$instance->addYear(2011);
		$instance->addMonth(9);
		$this->assertSame('2012-08-01 00:00:00', $instance->value());

		$instance->addMonth(24);
		$this->assertSame('2014-08-01 00:00:00', $instance->value());

		$instance->addMonth(24);
		$this->assertSame('2016-08-01 00:00:00', $instance->value());
	}

	/**
	 * @test
	 * @covers ::subMonth
	 */
	public function subMonth()
	{
		$instance = new TimeType();

		$instance->subMonth(1);
		$this->assertSame('-0001-12-01 00:00:00', $instance->value());

		$instance->subMonth(9);
		$this->assertSame('-0001-03-01 00:00:00', $instance->value());

		$instance->subMonth(72);
		$this->assertSame('-0007-03-01 00:00:00', $instance->value());

		$instance->addYear(2020);
		$instance->subMonth(2);
		$this->assertSame('2013-01-01 00:00:00', $instance->value());

		$instance->subMonth(11);
		$this->assertSame('2012-02-01 00:00:00', $instance->value());
	}

	/**
	 * @test
	 * @covers ::addDay
	 */
	public function addDay()
	{
		$instance = new TimeType();

		$instance->addDay(1);
		$this->assertSame('0000-01-02 00:00:00', $instance->value());

		$instance->addDay(9);
		$this->assertSame('0000-01-11 00:00:00', $instance->value());

		$instance->addYear(2011);
		$instance->addDay(21 + 365 + 27);
		$this->assertSame('2012-02-28 00:00:00', $instance->value());

		$instance->addDay(1);
		$this->assertSame('2012-02-29 00:00:00', $instance->value());

		$instance->addDay(1);
		$this->assertSame('2012-03-01 00:00:00', $instance->value());

		$instance->addDay(365 * 3);
		$this->assertSame('2015-03-01 00:00:00', $instance->value());

		$instance->addDay(365);
		$this->assertSame('2016-02-29 00:00:00', $instance->value());
	}

	/**
	 * @test
	 * @covers ::addHour
	 */
	public function addHour()
	{
		$instance = new TimeType();

		$instance->addHour(1);
		$this->assertSame('0000-01-01 01:00:00', $instance->value());

		$instance->addHour(9);
		$this->assertSame('0000-01-01 10:00:00', $instance->value());

		$instance->addYear(2012);
		$instance->addMonth(1);
		$instance->addDay(27);
		$this->assertSame('2012-02-28 10:00:00', $instance->value());

		$instance->addHour(20);
		$this->assertSame('2012-02-29 06:00:00', $instance->value());

		$instance->addHour(24);
		$this->assertSame('2012-03-01 06:00:00', $instance->value());

		$instance->addHour(24 * 365 * 3);
		$this->assertSame('2015-03-01 06:00:00', $instance->value());

		$instance->addHour(24 * 365);
		$this->assertSame('2016-02-29 06:00:00', $instance->value());
	}

	/**
	 * @test
	 * @covers ::addMinute
	 */
	public function addMinute()
	{
		$instance = new TimeType();

		$instance->addMinute(1);
		$this->assertSame('0000-01-01 00:01:00', $instance->value());

		$instance->addMinute(9);
		$this->assertSame('0000-01-01 00:10:00', $instance->value());

		$instance->addYear(2012);
		$instance->addMonth(1);
		$instance->addDay(27);
		$instance->addHour(23);
		$this->assertSame('2012-02-28 23:10:00', $instance->value());

		$instance->addMinute(50);
		$this->assertSame('2012-02-29 00:00:00', $instance->value());

		$instance->addMinute(60 * 24);
		$this->assertSame('2012-03-01 00:00:00', $instance->value());

		$instance->addMinute(60 * 24 * 365 * 3);
		$this->assertSame('2015-03-01 00:00:00', $instance->value());

		$instance->addMinute(60 * 24 * 365);
		$this->assertSame('2016-02-29 00:00:00', $instance->value());
	}

	/**
	 * @test
	 * @covers ::addSecond
	 */
	public function addSecond()
	{
		$instance = new TimeType();

		$instance->addSecond(1);
		$this->assertSame('0000-01-01 00:00:01', $instance->value());

		$instance->addSecond(59);
		$this->assertSame('0000-01-01 00:01:00', $instance->value());

		$instance->addYear(2012);
		$instance->addMonth(1);
		$instance->addDay(27);
		$instance->addHour(23);
		$instance->addMinute(58);
		$instance->addSecond(59);
		$this->assertSame('2012-02-28 23:59:59', $instance->value());

		$instance->addSecond(1);
		$this->assertSame('2012-02-29 00:00:00', $instance->value());

		$instance->addSecond(60 * 60 * 24);
		$this->assertSame('2012-03-01 00:00:00', $instance->value());

		$instance->addSecond(60 * 60 * 24 * 365 * 3);
		$this->assertSame('2015-03-01 00:00:00', $instance->value());

		$instance->addSecond(60 * 60 * 24 * 365);
		$this->assertSame('2016-02-29 00:00:00', $instance->value());
	}
}
