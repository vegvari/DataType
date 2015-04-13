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

	public function testCastNaturalWithZero()
	{
		$data = _int::castNatural(0);
		$this->assertSame(0, $data);
	}

	public function testCastNaturalWithPositive()
	{
		$data = _int::castNatural(1);
		$this->assertSame(1, $data);
	}

	public function testCastNaturalWithNegative()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$data = _int::castNatural(-1);
	}

	public function testCastPositiveWithZero()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$data = _int::castPositive(0);
	}

	public function testCastPositiveWithPositive()
	{
		$data = _int::castPositive(1);
		$this->assertSame(1, $data);
	}

	public function testCastPositiveWithNegative()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$data = _int::castPositive(-1);
	}

	public function testCastNegativeWithZero()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$data = _int::castNegative(0);
	}

	public function testCastNegativeWithPositive()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$data = _int::castNegative(1);
	}

	public function testCastNegativeWithNegative()
	{
		$data = _int::castNegative(-1);
		$this->assertSame(-1, $data);
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

	public function testRand()
	{
		$instance = _int::rand();
		$this->assertTrue($instance instanceof _int);
	}

	public function testNeg()
	{
		$instance = _int::create(1);
		$this->assertTrue($instance->neg() instanceof _float);
		$this->assertSame(-1.0, $instance->neg()->value());

		$instance = _int::create(-1);
		$this->assertTrue($instance->neg() instanceof _float);
		$this->assertSame(1.0, $instance->neg()->value());
	}

	public function testAdd()
	{
		$instance = _int::create(1);
		$this->assertTrue($instance->add(1) instanceof _float);
		$this->assertSame(2.0, $instance->add(1)->value());

		$this->assertTrue($instance->add(_float::create(1)) instanceof _float);
		$this->assertSame(2.0, $instance->add(_float::create(1))->value());
	}

	public function testSub()
	{
		$instance = _int::create(1);
		$this->assertTrue($instance->sub(1) instanceof _float);
		$this->assertSame(0.0, $instance->sub(1)->value());

		$this->assertTrue($instance->sub(_float::create(1)) instanceof _float);
		$this->assertSame(0.0, $instance->sub(_float::create(1))->value());
	}

	public function testMul()
	{
		$instance = _int::create(2);
		$this->assertTrue($instance->mul(5) instanceof _float);
		$this->assertSame(10.0, $instance->mul(5)->value());

		$this->assertTrue($instance->mul(_float::create(5)) instanceof _float);
		$this->assertSame(10.0, $instance->mul(_float::create(5))->value());
	}

	public function testDiv()
	{
		$instance = _int::create(10);
		$this->assertTrue($instance->div(2) instanceof _float);
		$this->assertSame(5.0, $instance->div(2)->value());

		$this->assertTrue($instance->div(_float::create(2)) instanceof _float);
		$this->assertSame(5.0, $instance->div(_float::create(2))->value());
	}

	public function testMod()
	{
		$instance = _int::create(10);
		$this->assertTrue($instance->mod(2) instanceof _float);
		$this->assertSame(0.0, $instance->mod(2)->value());

		$this->assertTrue($instance->mod(_float::create(2)) instanceof _float);
		$this->assertSame(0.0, $instance->mod(_float::create(2))->value());
	}

	public function testExp()
	{
		$instance = _int::create(10);
		$this->assertTrue($instance->exp(2) instanceof _float);
		$this->assertSame(100.0, $instance->exp(2)->value());

		$this->assertTrue($instance->exp(_float::create(2)) instanceof _float);
		$this->assertSame(100.0, $instance->exp(_float::create(2))->value());
	}

	public function testSqrt()
	{
		$instance = _int::create(100);
		$this->assertTrue($instance->sqrt() instanceof _float);
		$this->assertSame(10.0, $instance->sqrt()->value());
	}

	public function testRoot()
	{
		$instance = _int::create(27);
		$this->assertTrue($instance->root(3) instanceof _float);
		$this->assertSame(3.0, $instance->root(3)->value());

		$this->assertTrue($instance->root(_float::create(3)) instanceof _float);
		$this->assertSame(3.0, $instance->root(_float::create(3))->value());
	}

	public function testEq()
	{
		$instance = _float::create(1);
		$this->assertSame(false, $instance->eq(null));
		$this->assertSame(false, $instance->eq(0));
		$this->assertSame(true,  $instance->eq(1));
		$this->assertSame(false, $instance->eq(2));
	}

	public function testEqWithNull()
	{
		$instance = _float::create();
		$this->assertSame(true, $instance->eq(null));
	}

	public function testNe()
	{
		$instance = _float::create(1);
		$this->assertSame(true,  $instance->ne(null));
		$this->assertSame(true,  $instance->ne(0));
		$this->assertSame(false, $instance->ne(1));
		$this->assertSame(true,  $instance->ne(2));
	}

	public function testNeWithNull()
	{
		$instance = _float::create();
		$this->assertSame(false, $instance->eq(1));
	}

	public function testGt()
	{
		$instance = _float::create(1);
		$this->assertSame(true,  $instance->gt(0));
		$this->assertSame(false, $instance->gt(1));
		$this->assertSame(false, $instance->gt(2));
	}

	public function testGtWithNull()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = _float::create(1);
		$instance->gt(null);
	}

	public function testGte()
	{
		$instance = _float::create(1);
		$this->assertSame(true,  $instance->gte(0));
		$this->assertSame(true,  $instance->gte(1));
		$this->assertSame(false, $instance->gte(2));
	}

	public function testGteWithNull()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = _float::create(1);
		$instance->gte(null);
	}

	public function testLt()
	{
		$instance = _float::create(1);
		$this->assertSame(false, $instance->lt(0));
		$this->assertSame(false, $instance->lt(1));
		$this->assertSame(true,  $instance->lt(2));
	}

	public function testLtWithNull()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = _float::create(1);
		$instance->lt(null);
	}

	public function testLte()
	{
		$instance = _float::create(1);
		$this->assertSame(false, $instance->lte(0));
		$this->assertSame(true,  $instance->lte(1));
		$this->assertSame(true,  $instance->lte(2));
	}

	public function testLteWithNull()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = _float::create(1);
		$instance->lte(null);
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
     * @dataProvider primeDataProvider
     */
	public function testIsPrime($data, $expected)
	{
		$instance = _int::create($data);
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
