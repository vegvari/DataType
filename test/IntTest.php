<?php

namespace Data\Type;

class IntTest extends \PHPUnit_Framework_TestCase
{
	public function testInstantiateWithoutArg()
	{
		$this->setExpectedException('PHPUnit_Framework_Error_Warning');
		$instance = new Int();
	}

	public function testCreate()
	{
		$instance = Int::create(1);
		$this->assertSame(1, $instance->value());
	}

	public function testCast()
	{
		$data = Int::cast(1);
		$this->assertSame(1, $data);
	}

	public function testCastNullable()
	{
		$data = Int::castNullable(null);
		$this->assertSame(null, $data);
	}

	public function testCastSilent()
	{
		$data = Int::castSilent('test');
		$this->assertSame(null, $data);
	}

	/**
     * @dataProvider toStringDataProvider
     */
	public function testToString($data, $expected)
	{
		$instance = Int::create($data);
		$this->assertSame($expected, (string) $instance);
	}

	public function toStringDataProvider()
	{
		return array(
			array(0, '0'),
			array(1, '1'),
		);
	}

	/**
     * @dataProvider validDataProvider
     */
	public function testValid($data, $expected)
	{
		$instance = Int::create($data);
		$this->assertSame($expected, $instance->value());
	}

	public function validDataProvider()
	{
		return array(
			array(Int::create(0.0), 0),
			array(Int::create(1.0), 1),
			array(false,            0),
			array(true,             1),
			array(0.0,              0),
			array(1.0,              1),
			array(0,                0),
			array(1,                1),
			array('0',              0),
			array('1',              1),

			array(-1.0,             -1),
			array(2.0,              2),
			array(-1,               -1),
			array(2,                2),

			array('-1',             -1),
			array('2',              2),

			array('000',            0),
			array('000.000',        0),
			array('-1.00000',       -1),
			array('2.000000',       2),

			array('1e2',            100),
			array('-1e2',           -100),
			array('1E2',            100),
			array('-1E2',           -100),
			array('1e+2',           100),
			array('-1e+2',          -100),
			array('1E+2',           100),
			array('-1E+2',          -100),

			array('0e0',            0),
			array('000e000',        0),
			array('1e0',            1),
			array('1e000',          1),
			array('1e001',          10),
		);
	}

	/**
     * @dataProvider invalidDataProvider
     */
	public function testInvalid($data, $expected)
	{
		$this->setExpectedException($expected);
		$instance = Int::create($data);
	}

	public function invalidDataProvider()
	{
		return array(
			array(null,                 '\InvalidArgumentException'),
			array(array(),              '\InvalidArgumentException'),
			array(new \stdClass(),      '\InvalidArgumentException'),
			array(fopen(__FILE__, 'r'), '\InvalidArgumentException'),
			array('1e-2',               '\InvalidArgumentException'),
			array('-1e-2',              '\InvalidArgumentException'),
			array('1E-2',               '\InvalidArgumentException'),
			array('-1E-2',              '\InvalidArgumentException'),
			array('0.1',                '\InvalidArgumentException'),
			array('-0.1',               '\InvalidArgumentException'),
			array('10.1',               '\InvalidArgumentException'),
			array('-10.1',              '\InvalidArgumentException'),
			array('e',                  '\InvalidArgumentException'),
			array('0e',                 '\InvalidArgumentException'),
			array('0.0e',               '\InvalidArgumentException'),
			array('1e',                 '\InvalidArgumentException'),
			array('1.e',                '\InvalidArgumentException'),
			array('1.0e',               '\InvalidArgumentException'),
			array('on',                 '\InvalidArgumentException'),
			array('off',                '\InvalidArgumentException'),
			array('true',               '\InvalidArgumentException'),
			array('false',              '\InvalidArgumentException'),
			array('null',               '\InvalidArgumentException'),
		);
	}

	public function testIsEven()
	{
		$instance = Int::create(0);
		$this->assertSame(true, $instance->isEven());

		$instance = Int::create(1);
		$this->assertSame(false, $instance->isEven());

		$instance = Int::create(2);
		$this->assertSame(true, $instance->isEven());
	}

	public function testIsOdd()
	{
		$instance = Int::create(0);
		$this->assertSame(false, $instance->isOdd());

		$instance = Int::create(1);
		$this->assertSame(true, $instance->isOdd());

		$instance = Int::create(2);
		$this->assertSame(false, $instance->isOdd());
	}

	/**
     * @dataProvider primeDataPrivider
     */
	public function testIsPrime($data, $expected)
	{
		$instance = Int::create($data);
		$this->assertSame($expected, $instance->isPrime());
	}

	public function primeDataPrivider()
	{
		return array(
			array(-1, false),
			array(0,  false),
			array(1,  false),
			array(2,  true),
			array(3,  true),
			array(4,  false),
			array(5,  true),
			array(6,  false),
			array(7,  true),
			array(8,  false),
			array(9,  false),
			array(10, false),
			array(11, true),
		);
	}
}
