<?php

use Data\Type\Cast;
use Data\Type\IntType;
use Data\Type\BoolType;
use Data\Type\FloatType;
use Data\Type\StringType;
use Data\Type\DateTimeType;

/**
 * @coversDefaultClass \Data\Type\IntType
 */
class IntTypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers ::__toString
     */
    public function toString()
    {
        $instance = new IntType();
        $this->assertSame('', (string) $instance);

        $instance = new IntType(0);
        $this->assertSame('0', (string) $instance);

        $instance = new IntType(1);
        $this->assertSame('1', (string) $instance);
    }

    /**
     * @test
     * @dataProvider checkDataProvider
     * @covers       ::check
     */
    public function check($data, $expected)
    {
        $instance = new IntType($data);
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

            [true,                1],
            [1.0,                 1],
            [1,                   1],
            ['1',                 1],
            [new BoolType(true),  1],
            [new FloatType(1),    1],
            [new IntType(1),      1],
            [new StringType(1),   1],

            [false,               0],
            [0.0,                 0],
            [0,                   0],
            ['0',                 0],
            [new BoolType(false), 0],
            [new FloatType(0),    0],
            [new IntType(0),      0],
            [new StringType(0),   0],

            [-1.0,                -1],
            [2.0,                 2],
            [-1,                  -1],
            [2,                   2],
            ['-1',                -1],
            ['2',                 2],

            ['000',               0],
            ['000.000',           0],
            ['-1.00000',          -1],
            ['2.000000',          2],

            ['1e2',               100],
            ['-1e2',              -100],
            ['1E2',               100],
            ['-1E2',              -100],
            ['1e+2',              100],
            ['-1e+2',             -100],
            ['1E+2',              100],
            ['-1E+2',             -100],

            ['0e0',               0],
            ['000e000',           0],
            ['1e0',               1],
            ['1e000',             1],
            ['1e001',             10],

            [PHP_INT_MAX,         PHP_INT_MAX],
            [~PHP_INT_MAX,        ~PHP_INT_MAX],
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
        $instance = new IntType($data);
    }

    public function checkFailDataProvider()
    {
        return [
            [new StringType('a'),  '\Data\Type\Exceptions\InvalidIntException'],
            [[],                   '\Data\Type\Exceptions\InvalidIntException'],
            [new stdClass(),       '\Data\Type\Exceptions\InvalidIntException'],
            [fopen(__FILE__, 'r'), '\Data\Type\Exceptions\InvalidIntException'],
            ['1e-2',               '\Data\Type\Exceptions\InvalidIntException'],
            ['-1e-2',              '\Data\Type\Exceptions\InvalidIntException'],
            ['1E-2',               '\Data\Type\Exceptions\InvalidIntException'],
            ['-1E-2',              '\Data\Type\Exceptions\InvalidIntException'],
            ['0.1',                '\Data\Type\Exceptions\InvalidIntException'],
            ['-0.1',               '\Data\Type\Exceptions\InvalidIntException'],
            ['10.1',               '\Data\Type\Exceptions\InvalidIntException'],
            ['-10.1',              '\Data\Type\Exceptions\InvalidIntException'],
            ['e',                  '\Data\Type\Exceptions\InvalidIntException'],
            ['0e',                 '\Data\Type\Exceptions\InvalidIntException'],
            ['0.0e',               '\Data\Type\Exceptions\InvalidIntException'],
            ['1e',                 '\Data\Type\Exceptions\InvalidIntException'],
            ['1.e',                '\Data\Type\Exceptions\InvalidIntException'],
            ['1.0e',               '\Data\Type\Exceptions\InvalidIntException'],
            ['on',                 '\Data\Type\Exceptions\InvalidIntException'],
            ['off',                '\Data\Type\Exceptions\InvalidIntException'],
            ['true',               '\Data\Type\Exceptions\InvalidIntException'],
            ['false',              '\Data\Type\Exceptions\InvalidIntException'],
            ['null',               '\Data\Type\Exceptions\InvalidIntException'],
        ];
    }

    /**
     * @test
     * @covers ::isEven
     */
    public function isEven()
    {
        $instance = new IntType(0);
        $this->assertSame(true, $instance->isEven());

        $instance = new IntType(1);
        $this->assertSame(false, $instance->isEven());

        $instance = new IntType(2);
        $this->assertSame(true, $instance->isEven());
    }

    /**
     * @test
     * @covers ::isOdd
     */
    public function isOdd()
    {
        $instance = new IntType(0);
        $this->assertSame(false, $instance->isOdd());

        $instance = new IntType(1);
        $this->assertSame(true, $instance->isOdd());

        $instance = new IntType(2);
        $this->assertSame(false, $instance->isOdd());
    }

    /**
     * @test
     * @dataProvider isPrimeDataProvider
     * @covers       ::isPrime
     */
    public function isPrime($data, $expected)
    {
        $instance = new IntType($data);
        $this->assertSame($expected, $instance->isPrime());
    }

    public function isPrimeDataProvider()
    {
        return [
            [-1, false],
            [0,  false],
            [1,  false],
            [2,  true],
            [3,  true],
            [4,  false],
            [5,  true],
            [6,  false],
            [7,  true],
            [8,  false],
            [9,  false],
            [10, false],
            [11, true],
        ];
    }
}
