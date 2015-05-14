<?php

namespace Data\Type;

use stdClass;
use SplSubject;
use SplObserver;
use PHPUnit_Framework_TestCase;

class TimeTest extends PHPUnit_Framework_TestCase implements SplObserver
{
	public $observer_helper_value;

	public function update(SplSubject $subject)
	{
		$this->observer_helper_value = $subject->value();
	}

	public function testObserverUpdateOnAttach()
	{
		$this->observer_helper_value = null;

		$instance = new TimeType('2012-06-30 23:59:59');
		$instance->attach($this);
		$this->assertSame('2012-06-30 23:59:59', $this->observer_helper_value);
	}

	public function testObserverUpdateOnAttachExceptNull()
	{
		$this->observer_helper_value = 'no update';

		$instance = new TimeType();
		$instance->attach($this);
		$this->assertSame('no update', $this->observer_helper_value);
	}

	public function testObserverUpdateOnChange()
	{
		$this->observer_helper_value = null;

		$instance = new TimeType();
		$instance->attach($this);

		$instance->set('2012-06-30 23:59:59');
		$this->assertSame('2012-06-30 23:59:59', $this->observer_helper_value);

		$instance->set('2012-06-30 23:59:59');
		$this->assertSame('2012-06-30 23:59:59', $this->observer_helper_value);

		$instance->set(null);
		$this->assertSame(null, $this->observer_helper_value);
	}

	public function testNull()
	{
		$instance = new TimeType();
		$this->assertSame(null, $instance->value());
	}

	public function testMake()
	{
		$instance = new TimeType('2012-06-30 23:59:59');
		$this->assertSame('2012-06-30 23:59:59', $instance->value());
	}

	public function testToString()
	{
		$instance = new TimeType('2012-06-30 23:59:59');
		$this->assertSame('2012-06-30 23:59:59', (string) $instance);
	}

	/**
     * @dataProvider validDataProvider
     */
	public function testValid($data, $expected)
	{
		$instance = new TimeType($data);
		$this->assertSame($expected, $instance->value());
	}

	public function validDataProvider()
	{
		return [
			[new StringType('2012-06-30 23:59:59'), '2012-06-30 23:59:59'],
			['2012-06-30 23:59:59',                 '2012-06-30 23:59:59'],
			['2012-02-29 23:59:59',                 '2012-02-29 23:59:59'],
		];
	}

	/**
     * @dataProvider invalidDataProvider
     */
	public function testInvalid($data, $expected)
	{
		$this->setExpectedException($expected);
		$instance = new TimeType($data);
	}

	public function invalidDataProvider()
	{
		return array(
			array('',                    'InvalidArgumentException'),
			array('2011-02-29 23:59:59', 'InvalidArgumentException'),
			array(array(),               'InvalidArgumentException'),
			array(new stdClass(),        'InvalidArgumentException'),
			array(fopen(__FILE__, 'r'),  'InvalidArgumentException'),
			array(-1.0,                  'InvalidArgumentException'),
			array(2.0,                   'InvalidArgumentException'),
			array(-1,                    'InvalidArgumentException'),
			array(2,                     'InvalidArgumentException'),
			array('-1.0',                'InvalidArgumentException'),
			array('2.0',                 'InvalidArgumentException'),
			array('-1',                  'InvalidArgumentException'),
			array('2',                   'InvalidArgumentException'),
			array('on',                  'InvalidArgumentException'),
			array('off',                 'InvalidArgumentException'),
			array('true',                'InvalidArgumentException'),
			array('false',               'InvalidArgumentException'),
			array('null',                'InvalidArgumentException'),
		);
	}
}
