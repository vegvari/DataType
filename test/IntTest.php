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

		$instance = IntType::create(1);
		$instance->attach($this);
		$this->assertSame(1, $this->observer_helper_value);
	}

	public function testObserverUpdateOnAttachExceptNull()
	{
		$this->observer_helper_value = 'no update';

		$instance = IntType::create();
		$instance->attach($this);
		$this->assertSame('no update', $this->observer_helper_value);
	}

	public function testObserverUpdateOnChange()
	{
		$instance = IntType::create();
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
		$instance = IntType::create();
		$this->assertSame(null, $instance->value());
	}

	public function testMake()
	{
		$instance = IntType::create(1);
		$this->assertSame(1, $instance->value());
	}

	public function testCast()
	{
		$data = IntType::cast(1);
		$this->assertSame(1, $data);
	}

	public function testCastSilent()
	{
		$data = IntType::castSilent('test');
		$this->assertSame(null, $data);
	}

	public function testCastNaturalWithZero()
	{
		$data = IntType::castNatural(0);
		$this->assertSame(0, $data);
	}

	public function testCastNaturalWithPositive()
	{
		$data = IntType::castNatural(1);
		$this->assertSame(1, $data);
	}

	public function testCastNaturalWithNegative()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$data = IntType::castNatural(-1);
	}

	public function testCastPositiveWithZero()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$data = IntType::castPositive(0);
	}

	public function testCastPositiveWithPositive()
	{
		$data = IntType::castPositive(1);
		$this->assertSame(1, $data);
	}

	public function testCastPositiveWithNegative()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$data = IntType::castPositive(-1);
	}

	public function testCastNegativeWithZero()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$data = IntType::castNegative(0);
	}

	public function testCastNegativeWithPositive()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$data = IntType::castNegative(1);
	}

	public function testCastNegativeWithNegative()
	{
		$data = IntType::castNegative(-1);
		$this->assertSame(-1, $data);
	}

	/**
     * @dataProvider toStringDataProvider
     */
	public function testToString($data, $expected)
	{
		$instance = IntType::create($data);
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
		$instance = IntType::create($data);
		$this->assertSame($expected, $instance->value());
	}

	public function validDataProvider()
	{
		return array(
			array(BoolType::create(1),   1),
			array(FloatType::create(1),  1),
			array(IntType::create(1),    1),
			array(StringType::create(1), 1),
			array(IntType::create(0.0),  0),
			array(false,                 0),
			array(true,                  1),
			array(0.0,                   0),
			array(1.0,                   1),
			array(0,                     0),
			array(1,                     1),
			array('0',                   0),
			array('1',                   1),

			array(-1.0,                  -1),
			array(2.0,                   2),
			array(-1,                    -1),
			array(2,                     2),

			array('-1',                  -1),
			array('2',                   2),

			array('000',                 0),
			array('000.000',             0),
			array('-1.00000',            -1),
			array('2.000000',            2),

			array('1e2',                 100),
			array('-1e2',                -100),
			array('1E2',                 100),
			array('-1E2',                -100),
			array('1e+2',                100),
			array('-1e+2',               -100),
			array('1E+2',                100),
			array('-1E+2',               -100),

			array('0e0',                 0),
			array('000e000',             0),
			array('1e0',                 1),
			array('1e000',               1),
			array('1e001',               10),

			array(PHP_INT_MAX,           PHP_INT_MAX),
			array(~PHP_INT_MAX,          ~PHP_INT_MAX),
		);
	}

	/**
     * @dataProvider invalidDataProvider
     */
	public function testInvalid($data, $expected)
	{
		$this->setExpectedException($expected);
		$instance = IntType::create($data);
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

	public function testRand()
	{
		$instance = IntType::rand();
		$this->assertTrue($instance instanceof IntType);
	}

	public function testNeg()
	{
		$instance = IntType::create(1);
		$this->assertTrue($instance->neg() instanceof FloatType);
		$this->assertSame(-1.0, $instance->neg()->value());

		$instance = IntType::create(-1);
		$this->assertTrue($instance->neg() instanceof FloatType);
		$this->assertSame(1.0, $instance->neg()->value());
	}

	public function testAdd()
	{
		$instance = IntType::create(1);
		$this->assertTrue($instance->add(1) instanceof FloatType);
		$this->assertSame(2.0, $instance->add(1)->value());

		$this->assertTrue($instance->add(FloatType::create(1)) instanceof FloatType);
		$this->assertSame(2.0, $instance->add(FloatType::create(1))->value());
	}

	public function testSub()
	{
		$instance = IntType::create(1);
		$this->assertTrue($instance->sub(1) instanceof FloatType);
		$this->assertSame(0.0, $instance->sub(1)->value());

		$this->assertTrue($instance->sub(FloatType::create(1)) instanceof FloatType);
		$this->assertSame(0.0, $instance->sub(FloatType::create(1))->value());
	}

	public function testMul()
	{
		$instance = IntType::create(2);
		$this->assertTrue($instance->mul(5) instanceof FloatType);
		$this->assertSame(10.0, $instance->mul(5)->value());

		$this->assertTrue($instance->mul(FloatType::create(5)) instanceof FloatType);
		$this->assertSame(10.0, $instance->mul(FloatType::create(5))->value());
	}

	public function testDiv()
	{
		$instance = IntType::create(10);
		$this->assertTrue($instance->div(2) instanceof FloatType);
		$this->assertSame(5.0, $instance->div(2)->value());

		$this->assertTrue($instance->div(FloatType::create(2)) instanceof FloatType);
		$this->assertSame(5.0, $instance->div(FloatType::create(2))->value());
	}

	public function testMod()
	{
		$instance = IntType::create(10);
		$this->assertTrue($instance->mod(2) instanceof FloatType);
		$this->assertSame(0.0, $instance->mod(2)->value());

		$this->assertTrue($instance->mod(FloatType::create(2)) instanceof FloatType);
		$this->assertSame(0.0, $instance->mod(FloatType::create(2))->value());
	}

	public function testExp()
	{
		$instance = IntType::create(10);
		$this->assertTrue($instance->exp(2) instanceof FloatType);
		$this->assertSame(100.0, $instance->exp(2)->value());

		$this->assertTrue($instance->exp(FloatType::create(2)) instanceof FloatType);
		$this->assertSame(100.0, $instance->exp(FloatType::create(2))->value());
	}

	public function testSqrt()
	{
		$instance = IntType::create(100);
		$this->assertTrue($instance->sqrt() instanceof FloatType);
		$this->assertSame(10.0, $instance->sqrt()->value());
	}

	public function testRoot()
	{
		$instance = IntType::create(27);
		$this->assertTrue($instance->root(3) instanceof FloatType);
		$this->assertSame(3.0, $instance->root(3)->value());

		$this->assertTrue($instance->root(FloatType::create(3)) instanceof FloatType);
		$this->assertSame(3.0, $instance->root(FloatType::create(3))->value());
	}

	public function testEq()
	{
		$instance = FloatType::create(1);
		$this->assertSame(false, $instance->eq(null));
		$this->assertSame(false, $instance->eq(0));
		$this->assertSame(true,  $instance->eq(1));
		$this->assertSame(false, $instance->eq(2));
	}

	public function testEqWithNull()
	{
		$instance = FloatType::create();
		$this->assertSame(true, $instance->eq(null));
	}

	public function testNe()
	{
		$instance = FloatType::create(1);
		$this->assertSame(true,  $instance->ne(null));
		$this->assertSame(true,  $instance->ne(0));
		$this->assertSame(false, $instance->ne(1));
		$this->assertSame(true,  $instance->ne(2));
	}

	public function testNeWithNull()
	{
		$instance = FloatType::create();
		$this->assertSame(false, $instance->eq(1));
	}

	public function testGt()
	{
		$instance = FloatType::create(1);
		$this->assertSame(true,  $instance->gt(0));
		$this->assertSame(false, $instance->gt(1));
		$this->assertSame(false, $instance->gt(2));
	}

	public function testGtWithNull()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = FloatType::create(1);
		$instance->gt(null);
	}

	public function testGte()
	{
		$instance = FloatType::create(1);
		$this->assertSame(true,  $instance->gte(0));
		$this->assertSame(true,  $instance->gte(1));
		$this->assertSame(false, $instance->gte(2));
	}

	public function testGteWithNull()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = FloatType::create(1);
		$instance->gte(null);
	}

	public function testLt()
	{
		$instance = FloatType::create(1);
		$this->assertSame(false, $instance->lt(0));
		$this->assertSame(false, $instance->lt(1));
		$this->assertSame(true,  $instance->lt(2));
	}

	public function testLtWithNull()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = FloatType::create(1);
		$instance->lt(null);
	}

	public function testLte()
	{
		$instance = FloatType::create(1);
		$this->assertSame(false, $instance->lte(0));
		$this->assertSame(true,  $instance->lte(1));
		$this->assertSame(true,  $instance->lte(2));
	}

	public function testLteWithNull()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = FloatType::create(1);
		$instance->lte(null);
	}

	public function testIsEven()
	{
		$instance = IntType::create(0);
		$this->assertSame(true, $instance->isEven());

		$instance = IntType::create(1);
		$this->assertSame(false, $instance->isEven());

		$instance = IntType::create(2);
		$this->assertSame(true, $instance->isEven());
	}

	public function testIsOdd()
	{
		$instance = IntType::create(0);
		$this->assertSame(false, $instance->isOdd());

		$instance = IntType::create(1);
		$this->assertSame(true, $instance->isOdd());

		$instance = IntType::create(2);
		$this->assertSame(false, $instance->isOdd());
	}

	/**
     * @dataProvider primeDataProvider
     */
	public function testIsPrime($data, $expected)
	{
		$instance = IntType::create($data);
		$this->assertSame($expected, $instance->isPrime());
	}

	public function primeDataProvider()
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
