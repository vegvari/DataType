<?php

namespace Data\Type;

class IntTest extends \PHPUnit_Framework_TestCase implements \SplObserver
{
	public $observer_helper_value;

	public function update(\SplSubject $subject)
	{
		$this->observer_helper_value = $subject->value();
	}

	public function testObserverUpdateOnAttach()
	{
		$this->observer_helper_value = null;

		$instance = _int::create(1);
		$instance->attach($this);
		$this->assertSame(1, $this->observer_helper_value);
	}

	public function testObserverUpdateOnAttachExceptNull()
	{
		$this->observer_helper_value = 'no update';

		$instance = _int::create();
		$instance->attach($this);
		$this->assertSame('no update', $this->observer_helper_value);
	}

	public function testObserverUpdateOnChange()
	{
		$instance = _int::create();
		$instance->attach($this);

		$instance->set(1);
		$this->assertSame(1, $this->observer_helper_value);

		$instance->set(2);
		$this->assertSame(2, $this->observer_helper_value);

		$instance->set(null);
		$this->assertSame(null, $this->observer_helper_value);
	}

	public function testNull()
	{
		$instance = _int::create();
		$this->assertSame(null, $instance->value());
	}

	public function testMake()
	{
		$instance = _int::create(1);
		$this->assertSame(1, $instance->value());
	}

	public function testCast()
	{
		$data = _int::cast(1);
		$this->assertSame(1, $data);
	}

	public function testCastSilent()
	{
		$data = _int::castSilent('test');
		$this->assertSame(null, $data);
	}

	/**
     * @dataProvider toStringDataProvider
     */
	public function testToString($data, $expected)
	{
		$instance = _int::create($data);
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
		$instance = _int::create($data);
		$this->assertSame($expected, $instance->value());
	}

	public function validDataProvider()
	{
		return array(
			array(_bool::create(1),   1),
			array(_float::create(1),  1),
			array(_int::create(1),    1),
			array(_string::create(1), 1),
			array(_int::create(0.0),  0),
			array(false,              0),
			array(true,               1),
			array(0.0,                0),
			array(1.0,                1),
			array(0,                  0),
			array(1,                  1),
			array('0',                0),
			array('1',                1),

			array(-1.0,               -1),
			array(2.0,                2),
			array(-1,                 -1),
			array(2,                  2),

			array('-1',               -1),
			array('2',                2),

			array('000',              0),
			array('000.000',          0),
			array('-1.00000',         -1),
			array('2.000000',         2),

			array('1e2',              100),
			array('-1e2',             -100),
			array('1E2',              100),
			array('-1E2',             -100),
			array('1e+2',             100),
			array('-1e+2',            -100),
			array('1E+2',             100),
			array('-1E+2',            -100),

			array('0e0',              0),
			array('000e000',          0),
			array('1e0',              1),
			array('1e000',            1),
			array('1e001',            10),

			array(PHP_INT_MAX,        PHP_INT_MAX),
			array(~PHP_INT_MAX,       ~PHP_INT_MAX),
		);
	}

	/**
     * @dataProvider invalidDataProvider
     */
	public function testInvalid($data, $expected)
	{
		$this->setExpectedException($expected);
		$instance = _int::create($data);
	}

	public function invalidDataProvider()
	{
		return array(
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
		$instance = _int::create(0);
		$this->assertSame(true, $instance->isEven());

		$instance = _int::create(1);
		$this->assertSame(false, $instance->isEven());

		$instance = _int::create(2);
		$this->assertSame(true, $instance->isEven());
	}

	public function testIsOdd()
	{
		$instance = _int::create(0);
		$this->assertSame(false, $instance->isOdd());

		$instance = _int::create(1);
		$this->assertSame(true, $instance->isOdd());

		$instance = _int::create(2);
		$this->assertSame(false, $instance->isOdd());
	}

	/**
     * @dataProvider primeDataPrivider
     */
	public function testIsPrime($data, $expected)
	{
		$instance = _int::create($data);
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
