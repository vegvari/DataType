<?php

namespace Data\Type;

class boolTest extends \PHPUnit_Framework_TestCase
{
	/**
     * @dataProvider validDataProvider
     */
	public function testValid($data, $expected)
	{
		$instance = new Bool($data);
		$this->assertSame($expected, $instance->value());
	}

	/**
     * @dataProvider invalidDataProvider
     */
	public function testInvalid($data, $expected)
	{
		$this->setExpectedException($expected);
		$instance = new Bool($data);
	}

	public function testInstantiateWithoutArg()
	{
		$this->setExpectedException('PHPUnit_Framework_Error_Warning');
		$data = new Bool();
	}

	public function testCreate()
	{
		$data = Bool::create(true);
		$this->assertSame(true, $data->value());
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
