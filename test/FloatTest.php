<?php

namespace Data\Type;

class FloatTest extends \PHPUnit_Framework_TestCase
{
	public function testInstantiateWithoutArg()
	{
		$instance = new Float();
		$this->assertSame(null, $instance->value);
	}

	public function testMake()
	{
		$instance = Float::make(1.0);
		$this->assertSame(1.0, $instance->value);
	}

	public function testCast()
	{
		$data = Float::cast(1.0);
		$this->assertSame(1.0, $data);
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
		$instance = Float::make($data);
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
		$instance = Float::make($data);
		$this->assertSame($expected, $instance->value);
	}

	public function validDataProvider()
	{
		return array(
			array(Bool::make(1),   1.0),
			array(Float::make(1),  1.0),
			array(Int::make(1),    1.0),
			array(String::make(1), 1.0),
			array(false,             0.0),
			array(true,              1.0),
			array(0.0,               0.0),
			array(1.0,               1.0),
			array(0,                 0.0),
			array(1,                 1.0),
			array('0',               0.0),
			array('1',               1.0),

			array(-1.0,              -1.0),
			array(2.0,               2.0),
			array(-1,                -1.0),
			array(2,                 2.0),

			array('-1',              -1.0),
			array('2',               2.0),

			array('000',             0.0),
			array('000.000',         0.0),
			array('-1.00000',        -1.0),
			array('2.000000',        2.0),

			array('1e2',             100.0),
			array('-1e2',            -100.0),
			array('1E2',             100.0),
			array('-1E2',            -100.0),
			array('1e+2',            100.0),
			array('-1e+2',           -100.0),
			array('1E+2',            100.0),
			array('-1E+2',           -100.0),

			array('0e0',             0.0),
			array('000e000',         0.0),
			array('1e0',             1.0),
			array('1e000',           1.0),
			array('1e001',           10.0),

			array('1e-2',             0.01),
			array('-1e-2',            -0.01),
			array('1E-2',             0.01),
			array('-1E-2',            -0.01),
			array('0.1',             0.1),
			array('-0.1',            -0.1),
			array('10.1',            10.1),
			array('-10.1',           -10.1),
		);
	}

	/**
     * @dataProvider invalidDataProvider
     */
	public function testInvalid($data, $expected)
	{
		$this->setExpectedException($expected);
		$instance = Float::make($data);
	}

	public function invalidDataProvider()
	{
		return array(
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

	public function testRand()
	{
		$instance = Float::rand();
		$this->assertTrue($instance instanceof Float);
	}

	public function testNeg()
	{
		$instance = Float::make(1);
		$this->assertTrue($instance->neg() instanceof Float);
		$this->assertSame(-1.0, $instance->neg()->value);

		$instance = Float::make(-1);
		$this->assertTrue($instance->neg() instanceof Float);
		$this->assertSame(1.0, $instance->neg()->value);
	}

	public function testAdd()
	{
		$instance = Float::make(1);
		$this->assertTrue($instance->add(1) instanceof Float);
		$this->assertSame(2.0, $instance->add(1)->value);

		$this->assertTrue($instance->add(Float::make(1)) instanceof Float);
		$this->assertSame(2.0, $instance->add(Float::make(1))->value);
	}

	public function testSub()
	{
		$instance = Float::make(1);
		$this->assertTrue($instance->sub(1) instanceof Float);
		$this->assertSame(0.0, $instance->sub(1)->value);

		$this->assertTrue($instance->sub(Float::make(1)) instanceof Float);
		$this->assertSame(0.0, $instance->sub(Float::make(1))->value);
	}

	public function testMul()
	{
		$instance = Float::make(2);
		$this->assertTrue($instance->mul(5) instanceof Float);
		$this->assertSame(10.0, $instance->mul(5)->value);

		$this->assertTrue($instance->mul(Float::make(5)) instanceof Float);
		$this->assertSame(10.0, $instance->mul(Float::make(5))->value);
	}

	public function testDiv()
	{
		$instance = Float::make(10);
		$this->assertTrue($instance->div(2) instanceof Float);
		$this->assertSame(5.0, $instance->div(2)->value);

		$this->assertTrue($instance->div(Float::make(2)) instanceof Float);
		$this->assertSame(5.0, $instance->div(Float::make(2))->value);
	}

	public function testMod()
	{
		$instance = Float::make(10);
		$this->assertTrue($instance->mod(2) instanceof Float);
		$this->assertSame(0.0, $instance->mod(2)->value);

		$this->assertTrue($instance->mod(Float::make(2)) instanceof Float);
		$this->assertSame(0.0, $instance->mod(Float::make(2))->value);
	}

	public function testExp()
	{
		$instance = Float::make(10);
		$this->assertTrue($instance->exp(2) instanceof Float);
		$this->assertSame(100.0, $instance->exp(2)->value);

		$this->assertTrue($instance->exp(Float::make(2)) instanceof Float);
		$this->assertSame(100.0, $instance->exp(Float::make(2))->value);
	}

	public function testSqrt()
	{
		$instance = Float::make(100);
		$this->assertTrue($instance->sqrt() instanceof Float);
		$this->assertSame(10.0, $instance->sqrt()->value);
	}

	public function testRoot()
	{
		$instance = Float::make(27);
		$this->assertTrue($instance->root(3) instanceof Float);
		$this->assertSame(3.0, $instance->root(3)->value);

		$this->assertTrue($instance->root(Float::make(3)) instanceof Float);
		$this->assertSame(3.0, $instance->root(Float::make(3))->value);
	}
}