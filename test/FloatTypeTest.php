<?php

use Data\Type\Cast;
use Data\Type\IntType;
use Data\Type\BoolType;
use Data\Type\TimeType;
use Data\Type\FloatType;
use Data\Type\StringType;

/**
 * @coversDefaultClass \Data\Type\FloatType
 */
class FloatTypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers ::__toString
     */
    public function toString()
    {
        $instance = new FloatType();
        $this->assertSame('', (string) $instance);

        $instance = new FloatType(0);
        $this->assertSame('0', (string) $instance);

        $instance = new FloatType(1);
        $this->assertSame('1', (string) $instance);
    }

    /**
     * @test
     * @dataProvider checkDataProvider
     * @covers       ::check
     */
    public function check($data, $expected)
    {
        $instance = new FloatType($data);
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

            [true,                1.0],
            [1.0,                 1.0],
            [1,                   1.0],
            ['1',                 1.0],
            [new BoolType(true),  1.0],
            [new FloatType(1),    1.0],
            [new IntType(1),      1.0],
            [new StringType(1),   1.0],

            [false,               0.0],
            [0.0,                 0.0],
            [0,                   0.0],
            ['0',                 0.0],
            [new BoolType(false), 0.0],
            [new FloatType(0),    0.0],
            [new IntType(0),      0.0],
            [new StringType(0),   0.0],

            [-1.0,                -1.0],
            [2.0,                 2.0],
            [-1,                  -1.0],
            [2,                   2.0],

            ['-1',                -1.0],
            ['2',                 2.0],

            ['000',               0.0],
            ['000.000',           0.0],
            ['-1.00000',          -1.0],
            ['2.000000',          2.0],

            ['1e2',               100.0],
            ['-1e2',              -100.0],
            ['1E2',               100.0],
            ['-1E2',              -100.0],
            ['1e+2',              100.0],
            ['-1e+2',             -100.0],
            ['1E+2',              100.0],
            ['-1E+2',             -100.0],

            ['0e0',               0.0],
            ['000e000',           0.0],
            ['1e0',               1.0],
            ['1e000',             1.0],
            ['1e001',             10.0],

            ['1e-2',              0.01],
            ['-1e-2',             -0.01],
            ['1E-2',              0.01],
            ['-1E-2',             -0.01],
            ['0.1',               0.1],
            ['-0.1',              -0.1],
            ['10.1',              10.1],
            ['-10.1',             -10.1],
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
        $instance = new FloatType($data);
    }

    public function checkFailDataProvider()
    {
        return [
            [new StringType('a'),  '\Data\Type\Exceptions\InvalidFloatException'],
            [[],                   '\Data\Type\Exceptions\InvalidFloatException'],
            [new stdClass(),       '\Data\Type\Exceptions\InvalidFloatException'],
            [fopen(__FILE__, 'r'), '\Data\Type\Exceptions\InvalidFloatException'],
            ['e',                  '\Data\Type\Exceptions\InvalidFloatException'],
            ['0e',                 '\Data\Type\Exceptions\InvalidFloatException'],
            ['0.0e',               '\Data\Type\Exceptions\InvalidFloatException'],
            ['1e',                 '\Data\Type\Exceptions\InvalidFloatException'],
            ['1.e',                '\Data\Type\Exceptions\InvalidFloatException'],
            ['1.0e',               '\Data\Type\Exceptions\InvalidFloatException'],
            ['on',                 '\Data\Type\Exceptions\InvalidFloatException'],
            ['off',                '\Data\Type\Exceptions\InvalidFloatException'],
            ['true',               '\Data\Type\Exceptions\InvalidFloatException'],
            ['false',              '\Data\Type\Exceptions\InvalidFloatException'],
            ['null',               '\Data\Type\Exceptions\InvalidFloatException'],
        ];
    }
}
