<?php

namespace Data\Type;

class BoolTest extends \PHPUnit_Framework_TestCase
{
	public function testNull()
	{
		$instance = Bool::create();
		$this->assertSame(null, $instance->value());
	}

	public function testMake()
	{
		$instance = Bool::create(true);
		$this->assertSame(true, $instance->value());
	}

	public function testCast()
	{
		$data = Bool::cast(true);
		$this->assertSame(true, $data);
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
			array(Bool::create(1),     true),
			array(Float::create(1),    true),
			array(Int::create(1),      true),
			array(String::create(1),   true),
			array(Bool::create(false), false),
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
