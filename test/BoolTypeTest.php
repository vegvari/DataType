<?php

use Data\Type\Cast;
use Data\Type\IntType;
use Data\Type\BoolType;
use Data\Type\TimeType;
use Data\Type\FloatType;
use Data\Type\StringType;

/**
 * @coversDefaultClass \Data\Type\BoolType
 */
class BoolTypeTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 * @covers ::attach
	 * @covers ::detach
	 * @covers ::notify
	 */
    public function observer()
    {
    	$instance = new BoolType();

        $observer = $this->getMockBuilder('SplObserver')
                         ->setMethods(['update'])
                         ->getMock();

        $observer->expects($this->exactly(2))
                 ->method('update')
                 ->with($this->equalTo($instance));

		// no update on attach because value is null
        $instance->attach($observer);

        // first update
        $instance->set(true);

        // no update because value is not changed
        $instance->set(true);

        $instance->detach($observer);

        // update, but observer detached
        $instance->set(false);

        // second update on attach because value is not null
        $instance->attach($observer);
    }

	/**
     * @test
     * @covers ::__toString
     */
	public function toString()
	{
		$instance = new BoolType(true);
		$this->assertSame('1', (string) $instance);

		$instance = new BoolType(false);
		$this->assertSame('0', (string) $instance);
	}

	/**
	 * @test
     * @dataProvider validDataProvider
     * @covers       ::check
     */
	public function check($data, $expected)
	{
		$instance = new BoolType($data);
		$this->assertSame($expected, $instance->get());
	}

	public function validDataProvider()
	{
		return [
			[null,                null],
			[new BoolType(true),  true],
			[new BoolType(false), false],
			[new FloatType(1),    true],
			[new FloatType(0),    false],
			[new IntType(1),      true],
			[new IntType(0),      false],
			[new StringType(1),   true],
			[new StringType(0),   false],
			[true,                true],
			[false,               false],
			[1.0,                 true],
			[0.0,                 false],
			[1,                   true],
			[0,                   false],
			['1',                 true],
			['0',                 false],
		];
	}

	/**
	 * @test
     * @dataProvider invalidDataProvider
     * @covers       ::check
     */
	public function checkFail($data, $expected)
	{
		$this->setExpectedException($expected);
		$instance = new BoolType($data);
	}

	public function invalidDataProvider()
	{
		return [
			[array(),              'InvalidArgumentException'],
			[new stdClass(),       'InvalidArgumentException'],
			[fopen(__FILE__, 'r'), 'InvalidArgumentException'],
			[-1.0,                 'InvalidArgumentException'],
			[2.0,                  'InvalidArgumentException'],
			[-1,                   'InvalidArgumentException'],
			[2,                    'InvalidArgumentException'],
			['-1.0',               'InvalidArgumentException'],
			['2.0',                'InvalidArgumentException'],
			['-1',                 'InvalidArgumentException'],
			['2',                  'InvalidArgumentException'],
			['on',                 'InvalidArgumentException'],
			['off',                'InvalidArgumentException'],
			['true',               'InvalidArgumentException'],
			['false',              'InvalidArgumentException'],
			['null',               'InvalidArgumentException'],
		];
	}
}
