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
     * @covers ::__toString
     */
    public function toString()
    {
        $instance = new BoolType();
        $this->assertSame('', (string) $instance);

        $instance = new BoolType(true);
        $this->assertSame('1', (string) $instance);

        $instance = new BoolType(false);
        $this->assertSame('0', (string) $instance);
    }

    /**
     * @test
     * @dataProvider checkDataProvider
     * @covers       ::check
     */
    public function check($data, $expected)
    {
        $instance = new BoolType($data);
        $this->assertSame($expected, $instance->value());
    }

    public function checkDataProvider()
    {
        return [
            [null,                null],
            ['',                  null],
            [new BoolType(),      null],
            [new FloatType(),     null],
            [new IntType(),       null],
            [new StringType(),    null],

            [true,                true],
            [1.0,                 true],
            [1,                   true],
            ['1',                 true],
            [new BoolType(true),  true],
            [new FloatType(1),    true],
            [new IntType(1),      true],
            [new StringType(1),   true],

            [false,               false],
            [0.0,                 false],
            [0,                   false],
            ['0',                 false],
            [new BoolType(false), false],
            [new FloatType(0),    false],
            [new IntType(0),      false],
            [new StringType(0),   false],
        ];
    }

    /**
     * @test
     * @dataProvider checkFailDataProvider
     * @covers       ::check
     */
    public function checkFail($data, $expected)
    {
        $this->setExpectedException($expected);
        $instance = new BoolType($data);
    }

    public function checkFailDataProvider()
    {
        return [
            [new FloatType(2),     '\Data\Type\Exceptions\InvalidBoolException'],
            [new IntType(2),       '\Data\Type\Exceptions\InvalidBoolException'],
            [new StringType('a'),  '\Data\Type\Exceptions\InvalidBoolException'],
            [[],                   '\Data\Type\Exceptions\InvalidBoolException'],
            [new stdClass(),       '\Data\Type\Exceptions\InvalidBoolException'],
            [fopen(__FILE__, 'r'), '\Data\Type\Exceptions\InvalidBoolException'],
            [-1.0,                 '\Data\Type\Exceptions\InvalidBoolException'],
            [2.0,                  '\Data\Type\Exceptions\InvalidBoolException'],
            [-1,                   '\Data\Type\Exceptions\InvalidBoolException'],
            [2,                    '\Data\Type\Exceptions\InvalidBoolException'],
            ['-1.0',               '\Data\Type\Exceptions\InvalidBoolException'],
            ['2.0',                '\Data\Type\Exceptions\InvalidBoolException'],
            ['-1',                 '\Data\Type\Exceptions\InvalidBoolException'],
            ['2',                  '\Data\Type\Exceptions\InvalidBoolException'],
            ['on',                 '\Data\Type\Exceptions\InvalidBoolException'],
            ['off',                '\Data\Type\Exceptions\InvalidBoolException'],
            ['true',               '\Data\Type\Exceptions\InvalidBoolException'],
            ['false',              '\Data\Type\Exceptions\InvalidBoolException'],
            ['null',               '\Data\Type\Exceptions\InvalidBoolException'],
        ];
    }
}
