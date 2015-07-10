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

    // public function testNeg()
    // {
    //  $instance = new FloatType(1);
    //  $this->assertTrue($instance->neg() instanceof FloatType);
    //  $this->assertSame(-1.0, $instance->neg()->value());

    //  $instance = new FloatType(-1);
    //  $this->assertTrue($instance->neg() instanceof FloatType);
    //  $this->assertSame(1.0, $instance->neg()->value());
    // }

    // public function testAdd()
    // {
    //  $instance = new FloatType(1);
    //  $this->assertTrue($instance->add(1) instanceof FloatType);
    //  $this->assertSame(2.0, $instance->add(1)->value());

    //  $this->assertTrue($instance->add(new FloatType(1)) instanceof FloatType);
    //  $this->assertSame(2.0, $instance->add(new FloatType(1))->value());
    // }

    // public function testSub()
    // {
    //  $instance = new FloatType(1);
    //  $this->assertTrue($instance->sub(1) instanceof FloatType);
    //  $this->assertSame(0.0, $instance->sub(1)->value());

    //  $this->assertTrue($instance->sub(new FloatType(1)) instanceof FloatType);
    //  $this->assertSame(0.0, $instance->sub(new FloatType(1))->value());
    // }

    // public function testMul()
    // {
    //  $instance = new FloatType(2);
    //  $this->assertTrue($instance->mul(5) instanceof FloatType);
    //  $this->assertSame(10.0, $instance->mul(5)->value());

    //  $this->assertTrue($instance->mul(new FloatType(5)) instanceof FloatType);
    //  $this->assertSame(10.0, $instance->mul(new FloatType(5))->value());
    // }

    // public function testDiv()
    // {
    //  $instance = new FloatType(10);
    //  $this->assertTrue($instance->div(2) instanceof FloatType);
    //  $this->assertSame(5.0, $instance->div(2)->value());

    //  $this->assertTrue($instance->div(new FloatType(2)) instanceof FloatType);
    //  $this->assertSame(5.0, $instance->div(new FloatType(2))->value());
    // }

    // public function testMod()
    // {
    //  $instance = new FloatType(10);
    //  $this->assertTrue($instance->mod(2) instanceof FloatType);
    //  $this->assertSame(0.0, $instance->mod(2)->value());

    //  $this->assertTrue($instance->mod(new FloatType(2)) instanceof FloatType);
    //  $this->assertSame(0.0, $instance->mod(new FloatType(2))->value());
    // }

    // public function testExp()
    // {
    //  $instance = new FloatType(10);
    //  $this->assertTrue($instance->exp(2) instanceof FloatType);
    //  $this->assertSame(100.0, $instance->exp(2)->value());

    //  $this->assertTrue($instance->exp(new FloatType(2)) instanceof FloatType);
    //  $this->assertSame(100.0, $instance->exp(new FloatType(2))->value());
    // }

    // public function testSqrt()
    // {
    //  $instance = new FloatType(100);
    //  $this->assertTrue($instance->sqrt() instanceof FloatType);
    //  $this->assertSame(10.0, $instance->sqrt()->value());
    // }

    // public function testRoot()
    // {
    //  $instance = new FloatType(27);
    //  $this->assertTrue($instance->root(3) instanceof FloatType);
    //  $this->assertSame(3.0, $instance->root(3)->value());

    //  $this->assertTrue($instance->root(new FloatType(3)) instanceof FloatType);
    //  $this->assertSame(3.0, $instance->root(new FloatType(3))->value());
    // }

    // public function testEq()
    // {
    //  $instance = new FloatType(1);
    //  $this->assertSame(false, $instance->eq(null));
    //  $this->assertSame(false, $instance->eq(0));
    //  $this->assertSame(true,  $instance->eq(1));
    //  $this->assertSame(false, $instance->eq(2));
    // }

    // public function testEqWithNull()
    // {
    //  $instance = new FloatType();
    //  $this->assertSame(true, $instance->eq(null));
    // }

    // public function testNe()
    // {
    //  $instance = new FloatType(1);
    //  $this->assertSame(true,  $instance->ne(null));
    //  $this->assertSame(true,  $instance->ne(0));
    //  $this->assertSame(false, $instance->ne(1));
    //  $this->assertSame(true,  $instance->ne(2));
    // }

    // public function testNeWithNull()
    // {
    //  $instance = new FloatType();
    //  $this->assertSame(false, $instance->eq(1));
    // }

    // public function testGt()
    // {
    //  $instance = new FloatType(1);
    //  $this->assertSame(true,  $instance->gt(0));
    //  $this->assertSame(false, $instance->gt(1));
    //  $this->assertSame(false, $instance->gt(2));
    // }

    // public function testGtWithNull()
    // {
    //  $this->setExpectedException('InvalidArgumentException');
    //  $instance = new FloatType(1);
    //  $instance->gt(null);
    // }

    // public function testGte()
    // {
    //  $instance = new FloatType(1);
    //  $this->assertSame(true,  $instance->gte(0));
    //  $this->assertSame(true,  $instance->gte(1));
    //  $this->assertSame(false, $instance->gte(2));
    // }

    // public function testGteWithNull()
    // {
    //  $this->setExpectedException('InvalidArgumentException');
    //  $instance = new FloatType(1);
    //  $instance->gte(null);
    // }

    // public function testLt()
    // {
    //  $instance = new FloatType(1);
    //  $this->assertSame(false, $instance->lt(0));
    //  $this->assertSame(false, $instance->lt(1));
    //  $this->assertSame(true,  $instance->lt(2));
    // }

    // public function testLtWithNull()
    // {
    //  $this->setExpectedException('InvalidArgumentException');
    //  $instance = new FloatType(1);
    //  $instance->lt(null);
    // }

    // public function testLte()
    // {
    //  $instance = new FloatType(1);
    //  $this->assertSame(false, $instance->lte(0));
    //  $this->assertSame(true,  $instance->lte(1));
    //  $this->assertSame(true,  $instance->lte(2));
    // }

    // public function testLteWithNull()
    // {
    //  $this->setExpectedException('InvalidArgumentException');
    //  $instance = new FloatType(1);
    //  $instance->lte(null);
    // }
}
