<?php

namespace Data\Type;

class FloatTest extends \PHPUnit_Framework_TestCase implements \SplObserver
{
	public $observer_helper_value;

	public function update(\SplSubject $subject)
	{
		$this->observer_helper_value = $subject->value();
	}

	public function testObserverUpdateOnAttach()
	{
		$this->observer_helper_value = null;

		$instance = _float::create(1.0);
		$instance->attach($this);
		$this->assertSame(1.0, $this->observer_helper_value);
	}

	public function testObserverUpdateOnAttachExceptNull()
	{
		$this->observer_helper_value = 'no update';

		$instance = _float::create();
		$instance->attach($this);
		$this->assertSame('no update', $this->observer_helper_value);
	}

	public function testObserverUpdateOnChange()
	{
		$instance = _float::create();
		$instance->attach($this);

		$instance->set(1.0);
		$this->assertSame(1.0, $this->observer_helper_value);

		$instance->set(2.0);
		$this->assertSame(2.0, $this->observer_helper_value);

		$instance->set(null);
		$this->assertSame(null, $this->observer_helper_value);
	}

	public function testNull()
	{
		$instance = _float::create();
		$this->assertSame(null, $instance->value());
	}

	public function testMake()
	{
		$instance = _float::create(1.0);
		$this->assertSame(1.0, $instance->value());
	}

	public function testCast()
	{
		$data = _float::cast(1.0);
		$this->assertSame(1.0, $data);
	}

	public function testCastSilent()
	{
		$data = _float::castSilent('test');
		$this->assertSame(null, $data);
	}

	/**
     * @dataProvider toStringDataProvider
     */
	public function testToString($data, $expected)
	{
		$instance = _float::create($data);
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
		$instance = _float::create($data);
		$this->assertSame($expected, $instance->value());
	}

	public function validDataProvider()
	{
		return array(
			array(_bool::create(1),   1.0),
			array(_float::create(1),  1.0),
			array(_int::create(1),    1.0),
			array(_string::create(1), 1.0),
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

			array('1e-2',             0.01),
			array('-1e-2',            -0.01),
			array('1E-2',             0.01),
			array('-1E-2',            -0.01),
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
		$instance = _float::create($data);
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
		$instance = _float::rand();
		$this->assertTrue($instance instanceof _float);
	}

	public function testNeg()
	{
		$instance = _float::create(1);
		$this->assertTrue($instance->neg() instanceof _float);
		$this->assertSame(-1.0, $instance->neg()->value());

		$instance = _float::create(-1);
		$this->assertTrue($instance->neg() instanceof _float);
		$this->assertSame(1.0, $instance->neg()->value());
	}

	public function testAdd()
	{
		$instance = _float::create(1);
		$this->assertTrue($instance->add(1) instanceof _float);
		$this->assertSame(2.0, $instance->add(1)->value());

		$this->assertTrue($instance->add(_float::create(1)) instanceof _float);
		$this->assertSame(2.0, $instance->add(_float::create(1))->value());
	}

	public function testSub()
	{
		$instance = _float::create(1);
		$this->assertTrue($instance->sub(1) instanceof _float);
		$this->assertSame(0.0, $instance->sub(1)->value());

		$this->assertTrue($instance->sub(_float::create(1)) instanceof _float);
		$this->assertSame(0.0, $instance->sub(_float::create(1))->value());
	}

	public function testMul()
	{
		$instance = _float::create(2);
		$this->assertTrue($instance->mul(5) instanceof _float);
		$this->assertSame(10.0, $instance->mul(5)->value());

		$this->assertTrue($instance->mul(_float::create(5)) instanceof _float);
		$this->assertSame(10.0, $instance->mul(_float::create(5))->value());
	}

	public function testDiv()
	{
		$instance = _float::create(10);
		$this->assertTrue($instance->div(2) instanceof _float);
		$this->assertSame(5.0, $instance->div(2)->value());

		$this->assertTrue($instance->div(_float::create(2)) instanceof _float);
		$this->assertSame(5.0, $instance->div(_float::create(2))->value());
	}

	public function testMod()
	{
		$instance = _float::create(10);
		$this->assertTrue($instance->mod(2) instanceof _float);
		$this->assertSame(0.0, $instance->mod(2)->value());

		$this->assertTrue($instance->mod(_float::create(2)) instanceof _float);
		$this->assertSame(0.0, $instance->mod(_float::create(2))->value());
	}

	public function testExp()
	{
		$instance = _float::create(10);
		$this->assertTrue($instance->exp(2) instanceof _float);
		$this->assertSame(100.0, $instance->exp(2)->value());

		$this->assertTrue($instance->exp(_float::create(2)) instanceof _float);
		$this->assertSame(100.0, $instance->exp(_float::create(2))->value());
	}

	public function testSqrt()
	{
		$instance = _float::create(100);
		$this->assertTrue($instance->sqrt() instanceof _float);
		$this->assertSame(10.0, $instance->sqrt()->value());
	}

	public function testRoot()
	{
		$instance = _float::create(27);
		$this->assertTrue($instance->root(3) instanceof _float);
		$this->assertSame(3.0, $instance->root(3)->value());

		$this->assertTrue($instance->root(_float::create(3)) instanceof _float);
		$this->assertSame(3.0, $instance->root(_float::create(3))->value());
	}
}
