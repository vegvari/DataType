<?php

namespace Data\Type;

class FloatTest extends \PHPUnit_Framework_TestCase
{
	public function testInstantiateWithoutArg()
	{
		$this->setExpectedException('PHPUnit_Framework_Error_Warning');
		$instance = new Float();
	}

	public function testCreate()
	{
		$instance = Float::create(1.0);
		$this->assertSame(1.0, $instance->value());
	}

	public function testCast()
	{
		$data = Float::cast(1.0);
		$this->assertSame(1.0, $data);
	}

	public function testCastNullable()
	{
		$data = Float::castNullable(null);
		$this->assertSame(null, $data);
	}

	public function testCastSilent()
	{
		$data = Float::castSilent('test');
		$this->assertSame(null, $data);
	}

	/**
     * @dataProvider toStringDataProvider
     */
	public function testToString($data, $expected)
	{
		$instance = Float::create($data);
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
		$instance = Float::create($data);
		$this->assertSame($expected, $instance->value());
	}

	public function validDataProvider()
	{
		return array(
			array(Float::create(0.0), 0.0),
			array(Float::create(1.0), 1.0),
			array(false,              0.0),
			array(true,               1.0),
			array(0.0,                0.0),
			array(1.0,                1.0),
			array(0,                  0.0),
			array(1,                  1.0),
			array('0',                0.0),
			array('1',                1.0),

			array(-1.0,               -1.0),
			array(2.0,                2.0),
			array(-1,                 -1.0),
			array(2,                  2.0),

			array('-1',               -1.0),
			array('2',                2.0),

			array('000',              0.0),
			array('000.000',          0.0),
			array('-1.00000',         -1.0),
			array('2.000000',         2.0),

			array('1e2',              100.0),
			array('-1e2',             -100.0),
			array('1E2',              100.0),
			array('-1E2',             -100.0),
			array('1e+2',             100.0),
			array('-1e+2',            -100.0),
			array('1E+2',             100.0),
			array('-1E+2',            -100.0),

			array('0e0',              0.0),
			array('000e000',          0.0),
			array('1e0',              1.0),
			array('1e000',            1.0),
			array('1e001',            10.0),

			array('1e-2',              0.01),
			array('-1e-2',             -0.01),
			array('1E-2',              0.01),
			array('-1E-2',             -0.01),
			array('0.1',              0.1),
			array('-0.1',             -0.1),
			array('10.1',             10.1),
			array('-10.1',            -10.1),
		);
	}

	/**
     * @dataProvider invalidDataProvider
     */
	public function testInvalid($data, $expected)
	{
		$this->setExpectedException($expected);
		$instance = Float::create($data);
	}

	public function invalidDataProvider()
	{
		return array(
			array(null,                 '\InvalidArgumentException'),
			array(array(),              '\InvalidArgumentException'),
			array(new \stdClass(),      '\InvalidArgumentException'),
			array(fopen(__FILE__, 'r'), '\InvalidArgumentException'),
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
}
