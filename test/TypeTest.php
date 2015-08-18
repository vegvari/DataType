<?php

use Data\Type\Type;
use Data\Type\Cast;
use Data\Type\IntType;
use Data\Type\BoolType;
use Data\Type\FloatType;
use Data\Type\StringType;
use Data\Type\DateTimeType;

/**
 * @coversDefaultClass \Data\Type\Type
 */
class TypeTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $this->assertSame('int', IntType::TYPE);
        $this->assertSame('bool', BoolType::TYPE);
        $this->assertSame('float', FloatType::TYPE);
        $this->assertSame('string', StringType::TYPE);
        $this->assertSame('string', DateTimeType::TYPE);
    }

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
     * @test
     * @dataProvider instanceProvider
     * @covers       ::attach
     * @covers       ::detach
     * @covers       ::notify
     * @covers       ::__clone
     */
    public function observer($instance, array $data)
    {
        $observer = $this->getMockBuilder('SplObserver')
                         ->setMethods(['update'])
                         ->getMock();

        $observer->expects($this->exactly(2))
                 ->method('update')
                 ->with($this->equalTo($instance));

        // no update on attach
        $instance->attach($observer);

        // first update
        $instance->set($data[0]);

        // no update because value is not changed
        $instance->set($data[0]);

        // second update
        $instance->set($data[1]);

        // detach and change again without the observers
        $instance->detach($observer);
        $instance->set($data[0]);

        // detach observers
        $clone = clone $instance;
        $clone->set($data[0]);
        $clone->set($data[1]);
    }

    public function instanceProvider()
    {
        return [
            [new BoolType(),     [0, 1]],
            [new FloatType(),    [0, 1]],
            [new IntType(),      [0, 1]],
            [new StringType(),   [0, 1]],
            [new DateTimeType(), ['now', 'tomorrow']],
        ];
    }
}
