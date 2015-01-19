<?php

namespace Data\Type;

class BoolTest extends \PHPUnit_Framework_TestCase
{
	public function testInstantiateWithoutArg()
	{
		$this->setExpectedException('PHPUnit_Framework_Error_Warning');
		$instance = new Bool();
	}

	public function testCreate()
	{
		$instance = Bool::create(true);
		$this->assertSame(true, $instance->value());
	}

	public function testCast()
	{
		$data = Bool::cast(true);
		$this->assertSame(true, $data);
	}

	public function testCastNullable()
	{
		$data = Bool::castNullable(null);
		$this->assertSame(null, $data);
	}

	public function testCastSilent()
	{
		$data = Bool::castSilent('test');
		$this->assertSame(null, $data);
	}

	/**
     * @dataProvider toStringDataProvider
     */
	public function testToString($data, $expected)
	{
		$instance = Bool::create($data);
		$this->assertSame($expected, (string) $instance);
	}

	public function toStringDataProvider()
	{
		return array(
			array(false, '0'),
			array(true,  '1'),
		);
	}

	/**
     * @dataProvider validDataProvider
     */
	public function testValid($data, $expected)
	{
		$instance = Bool::create($data);
		$this->assertSame($expected, $instance->value());
	}

	public function validDataProvider()
	{
		return array(
			array(Bool::create(false), false),
			array(Bool::create(true),  true),
			array(false,               false),
			array(true,                true),
			array(0.0,                 false),
			array(1.0,                 true),
			array(0,                   false),
			array(1,                   true),
			array('0',                 false),
			array('1',                 true),
		);
	}

	/**
     * @dataProvider invalidDataProvider
     */
	public function testInvalid($data, $expected)
	{
		$this->setExpectedException($expected);
		$instance = Bool::create($data);
	}

	public function invalidDataProvider()
	{
		return array(
			array(null,                 '\InvalidArgumentException'),
			array(array(),              '\InvalidArgumentException'),
			array(new \stdClass(),      '\InvalidArgumentException'),
			array(fopen(__FILE__, 'r'), '\InvalidArgumentException'),
			array(-1.0,                 '\InvalidArgumentException'),
			array(2.0,                  '\InvalidArgumentException'),
			array(-1,                   '\InvalidArgumentException'),
			array(2,                    '\InvalidArgumentException'),
			array('-1.0',               '\InvalidArgumentException'),
			array('2.0',                '\InvalidArgumentException'),
			array('-1',                 '\InvalidArgumentException'),
			array('2',                  '\InvalidArgumentException'),
			array('on',                 '\InvalidArgumentException'),
			array('off',                '\InvalidArgumentException'),
			array('true',               '\InvalidArgumentException'),
			array('false',              '\InvalidArgumentException'),
			array('null',               '\InvalidArgumentException'),
		);
	}
}
