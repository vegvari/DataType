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

		$instance = new FloatType(1.0);
		$instance->attach($this);
		$this->assertSame(1.0, $this->observer_helper_value);
	}

	public function testObserverUpdateOnAttachExceptNull()
	{
		$this->observer_helper_value = 'no update';

		$instance = new FloatType();
		$instance->attach($this);
		$this->assertSame('no update', $this->observer_helper_value);
	}

	public function testObserverUpdateOnChange()
	{
		$instance = new FloatType();
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
		$instance = new FloatType();
		$this->assertSame(null, $instance->value());
	}

	public function testMake()
	{
		$instance = new FloatType(1.0);
		$this->assertSame(1.0, $instance->value());
	}

	public function testCast()
	{
		$data = Cast::Float(1);
		$this->assertSame(1.0, $data);
	}

	public function testCastUnsignedWithZero()
	{
		$data = Cast::uFloat(0);
		$this->assertSame(0.0, $data);
	}

	public function testCastUnsignedWithPositive()
	{
		$data = Cast::uFloat(1);
		$this->assertSame(1.0, $data);
	}

	public function testCastUnsignedWithNegative()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$data = Cast::uFloat(-1.0);
	}

	public function testCastPositiveWithZero()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$data = Cast::pFloat(0.0);
	}

	public function testCastPositiveWithPositive()
	{
		$data = Cast::pFloat(1);
		$this->assertSame(1.0, $data);
	}

	public function testCastPositiveWithNegative()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$data = Cast::pFloat(-1.0);
	}

	public function testCastNegativeWithZero()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$data = Cast::nFloat(0.0);
	}

	public function testCastNegativeWithPositive()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$data = Cast::nFloat(1.0);
	}

	public function testCastNegativeWithNegative()
	{
		$data = Cast::nFloat(-1);
		$this->assertSame(-1.0, $data);
	}

	/**
     * @dataProvider toStringDataProvider
     */
	public function testToString($data, $expected)
	{
		$instance = new FloatType($data);
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
		$instance = new FloatType($data);
		$this->assertSame($expected, $instance->value());
	}

	public function validDataProvider()
	{
		return array(
			array(new BoolType(1),   1.0),
			array(new FloatType(1),  1.0),
			array(new IntType(1),    1.0),
			array(new StringType(1), 1.0),
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

			array('1e-2',            0.01),
			array('-1e-2',           -0.01),
			array('1E-2',            0.01),
			array('-1E-2',           -0.01),
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
		$instance = new FloatType($data);
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

	public function testNeg()
	{
		$instance = new FloatType(1);
		$this->assertTrue($instance->neg() instanceof FloatType);
		$this->assertSame(-1.0, $instance->neg()->value());

		$instance = new FloatType(-1);
		$this->assertTrue($instance->neg() instanceof FloatType);
		$this->assertSame(1.0, $instance->neg()->value());
	}

	public function testAdd()
	{
		$instance = new FloatType(1);
		$this->assertTrue($instance->add(1) instanceof FloatType);
		$this->assertSame(2.0, $instance->add(1)->value());

		$this->assertTrue($instance->add(new FloatType(1)) instanceof FloatType);
		$this->assertSame(2.0, $instance->add(new FloatType(1))->value());
	}

	public function testSub()
	{
		$instance = new FloatType(1);
		$this->assertTrue($instance->sub(1) instanceof FloatType);
		$this->assertSame(0.0, $instance->sub(1)->value());

		$this->assertTrue($instance->sub(new FloatType(1)) instanceof FloatType);
		$this->assertSame(0.0, $instance->sub(new FloatType(1))->value());
	}

	public function testMul()
	{
		$instance = new FloatType(2);
		$this->assertTrue($instance->mul(5) instanceof FloatType);
		$this->assertSame(10.0, $instance->mul(5)->value());

		$this->assertTrue($instance->mul(new FloatType(5)) instanceof FloatType);
		$this->assertSame(10.0, $instance->mul(new FloatType(5))->value());
	}

	public function testDiv()
	{
		$instance = new FloatType(10);
		$this->assertTrue($instance->div(2) instanceof FloatType);
		$this->assertSame(5.0, $instance->div(2)->value());

		$this->assertTrue($instance->div(new FloatType(2)) instanceof FloatType);
		$this->assertSame(5.0, $instance->div(new FloatType(2))->value());
	}

	public function testMod()
	{
		$instance = new FloatType(10);
		$this->assertTrue($instance->mod(2) instanceof FloatType);
		$this->assertSame(0.0, $instance->mod(2)->value());

		$this->assertTrue($instance->mod(new FloatType(2)) instanceof FloatType);
		$this->assertSame(0.0, $instance->mod(new FloatType(2))->value());
	}

	public function testExp()
	{
		$instance = new FloatType(10);
		$this->assertTrue($instance->exp(2) instanceof FloatType);
		$this->assertSame(100.0, $instance->exp(2)->value());

		$this->assertTrue($instance->exp(new FloatType(2)) instanceof FloatType);
		$this->assertSame(100.0, $instance->exp(new FloatType(2))->value());
	}

	public function testSqrt()
	{
		$instance = new FloatType(100);
		$this->assertTrue($instance->sqrt() instanceof FloatType);
		$this->assertSame(10.0, $instance->sqrt()->value());
	}

	public function testRoot()
	{
		$instance = new FloatType(27);
		$this->assertTrue($instance->root(3) instanceof FloatType);
		$this->assertSame(3.0, $instance->root(3)->value());

		$this->assertTrue($instance->root(new FloatType(3)) instanceof FloatType);
		$this->assertSame(3.0, $instance->root(new FloatType(3))->value());
	}

	public function testEq()
	{
		$instance = new FloatType(1);
		$this->assertSame(false, $instance->eq(null));
		$this->assertSame(false, $instance->eq(0));
		$this->assertSame(true,  $instance->eq(1));
		$this->assertSame(false, $instance->eq(2));
	}

	public function testEqWithNull()
	{
		$instance = new FloatType();
		$this->assertSame(true, $instance->eq(null));
	}

	public function testNe()
	{
		$instance = new FloatType(1);
		$this->assertSame(true,  $instance->ne(null));
		$this->assertSame(true,  $instance->ne(0));
		$this->assertSame(false, $instance->ne(1));
		$this->assertSame(true,  $instance->ne(2));
	}

	public function testNeWithNull()
	{
		$instance = new FloatType();
		$this->assertSame(false, $instance->eq(1));
	}

	public function testGt()
	{
		$instance = new FloatType(1);
		$this->assertSame(true,  $instance->gt(0));
		$this->assertSame(false, $instance->gt(1));
		$this->assertSame(false, $instance->gt(2));
	}

	public function testGtWithNull()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = new FloatType(1);
		$instance->gt(null);
	}

	public function testGte()
	{
		$instance = new FloatType(1);
		$this->assertSame(true,  $instance->gte(0));
		$this->assertSame(true,  $instance->gte(1));
		$this->assertSame(false, $instance->gte(2));
	}

	public function testGteWithNull()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = new FloatType(1);
		$instance->gte(null);
	}

	public function testLt()
	{
		$instance = new FloatType(1);
		$this->assertSame(false, $instance->lt(0));
		$this->assertSame(false, $instance->lt(1));
		$this->assertSame(true,  $instance->lt(2));
	}

	public function testLtWithNull()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = new FloatType(1);
		$instance->lt(null);
	}

	public function testLte()
	{
		$instance = new FloatType(1);
		$this->assertSame(false, $instance->lte(0));
		$this->assertSame(true,  $instance->lte(1));
		$this->assertSame(true,  $instance->lte(2));
	}

	public function testLteWithNull()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = new FloatType(1);
		$instance->lte(null);
	}
}
