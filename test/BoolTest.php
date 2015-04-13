<?php

namespace Data\Type;

class BoolTest extends \PHPUnit_Framework_TestCase implements \SplObserver
{
	public $observer_helper_value;

	public function update(\SplSubject $subject)
	{
		$this->observer_helper_value = $subject->value();
	}

	public function testObserverUpdateOnAttach()
	{
		$this->observer_helper_value = null;

		$instance = BoolType::create(true);
		$instance->attach($this);
		$this->assertSame(true, $this->observer_helper_value);
	}

	public function testObserverUpdateOnAttachExceptNull()
	{
		$this->observer_helper_value = 'no update';

		$instance = BoolType::create();
		$instance->attach($this);
		$this->assertSame('no update', $this->observer_helper_value);
	}

	public function testObserverUpdateOnChange()
	{
		$this->observer_helper_value = null;

		$instance = BoolType::create();
		$instance->attach($this);

		$instance->set(true);
		$this->assertSame(true, $this->observer_helper_value);

		$instance->set(false);
		$this->assertSame(false, $this->observer_helper_value);

		$instance->set(null);
		$this->assertSame(null, $this->observer_helper_value);
	}

	public function testNull()
	{
		$instance = BoolType::create();
		$this->assertSame(null, $instance->value());
	}

	public function testMake()
	{
		$instance = BoolType::create(true);
		$this->assertSame(true, $instance->value());
	}

	public function testCast()
	{
		$data = BoolType::cast(true);
		$this->assertSame(true, $data);
	}

	public function testCastSilent()
	{
		$data = BoolType::castSilent('test');
		$this->assertSame(null, $data);
	}

	/**
     * @dataProvider toStringDataProvider
     */
	public function testToString($data, $expected)
	{
		$instance = BoolType::create($data);
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
		$instance = BoolType::create($data);
		$this->assertSame($expected, $instance->value());
	}

	public function validDataProvider()
	{
		return array(
			array(BoolType::create(1),     true),
			array(FloatType::create(1),    true),
			array(IntType::create(1),      true),
			array(StringType::create(1),   true),
			array(BoolType::create(false), false),
			array(false,                   false),
			array(true,                    true),
			array(0.0,                     false),
			array(1.0,                     true),
			array(0,                       false),
			array(1,                       true),
			array('0',                     false),
			array('1',                     true),
		);
	}

	/**
     * @dataProvider invalidDataProvider
     */
	public function testInvalid($data, $expected)
	{
		$this->setExpectedException($expected);
		$instance = BoolType::create($data);
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
