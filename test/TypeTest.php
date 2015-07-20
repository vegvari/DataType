<?php

use Data\Type\Cast;
use Data\Type\IntType;
use Data\Type\BoolType;
use Data\Type\FloatType;
use Data\Type\StringType;
use Data\Type\DateTimeType;

/**
 * @coversDefaultClass \Data\Type\BoolType
 */
class Type extends PHPUnit_Framework_TestCase
{
	/**
     * @dataProvider instanceProvider
     * @covers       ::isNull
     */
	public function testIsNull($instance, array $data)
	{
		$this->assertSame(true, $instance->isNull());

		$instance->set($data[0]);
		$this->assertSame(false, $instance->isNull());
	}

	/**
     * @dataProvider instanceProvider
     * @covers       ::isNotNull
     */
	public function testIsNotNull($instance, array $data)
	{
		$this->assertSame(false, $instance->isNotNull());

		$instance->set($data[0]);
		$this->assertSame(true, $instance->isNotNull());
	}

    /**
     * @test
     * @dataProvider instanceProvider
     * @covers       ::attach
     * @covers       ::detach
     * @covers       ::notify
     */
    public function observer($instance, array $data)
    {
        $observer = $this->getMockBuilder('SplObserver')
                         ->setMethods(['update'])
                         ->getMock();

        $observer->expects($this->exactly(2))
                 ->method('update')
                 ->with($this->equalTo($instance));

        // no update on attach because value is null
        $instance->attach($observer);

        // first update
        $instance->set($data[0]);

        // no update because value is not changed
        $instance->set($data[0]);

        $instance->detach($observer);

        // update, but observer detached
        $instance->set($data[1]);

        // second update on attach because value is not null
        $instance->attach($observer);
    }

    public function instanceProvider()
    {
    	return [
    		[new BoolType(),   ['0', '1']],
    		[new FloatType(),  ['0', '1']],
    		[new IntType(),    ['0', '1']],
    		[new StringType(), ['0', '1']],
    	];
    }
}
