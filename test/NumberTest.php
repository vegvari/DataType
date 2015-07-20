<?php

use Data\Type\Cast;
use Data\Type\IntType;
use Data\Type\BoolType;
use Data\Type\FloatType;
use Data\Type\StringType;
use Data\Type\DateTimeType;

/**
 * @coversDefaultClass \Data\Type\Number
 */
class NumberTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers ::neg
     */
    public function neg()
    {
        $instance = new IntType();
        $new_instance = $instance->neg();
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new FloatType();
        $new_instance = $instance->neg();
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new IntType(3);
        $new_instance = $instance->neg();
        $this->assertSame(3, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(-3.0, $new_instance->value());

        $instance = new FloatType(3);
        $new_instance = $instance->neg();
        $this->assertSame(3.0, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(-3.0, $new_instance->value());
    }

    /**
     * @test
     * @covers ::add
     */
    public function add()
    {
        $instance = new IntType();
        $new_instance = $instance->add(1);
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new FloatType();
        $new_instance = $instance->add(1);
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new IntType(3);
        $new_instance = $instance->add(1);
        $this->assertSame(3, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(4.0, $new_instance->value());

        $instance = new FloatType(3);
        $new_instance = $instance->add(1);
        $this->assertSame(3.0, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(4.0, $new_instance->value());
    }

    /**
     * @test
     * @covers ::sub
     */
    public function sub()
    {
        $instance = new IntType();
        $new_instance = $instance->sub(1);
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new FloatType();
        $new_instance = $instance->sub(1);
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new IntType(3);
        $new_instance = $instance->sub(1);
        $this->assertSame(3, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(2.0, $new_instance->value());

        $instance = new FloatType(3);
        $new_instance = $instance->sub(1);
        $this->assertSame(3.0, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(2.0, $new_instance->value());
    }

    /**
     * @test
     * @covers ::mul
     */
    public function mul()
    {
        $instance = new IntType();
        $new_instance = $instance->mul(2);
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new FloatType();
        $new_instance = $instance->mul(2);
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new IntType(3);
        $new_instance = $instance->mul(2);
        $this->assertSame(3, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(6.0, $new_instance->value());

        $instance = new FloatType(3);
        $new_instance = $instance->mul(2);
        $this->assertSame(3.0, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(6.0, $new_instance->value());
    }

    /**
     * @test
     * @covers ::div
     */
    public function div()
    {
        $instance = new IntType();
        $new_instance = $instance->div(2);
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new FloatType();
        $new_instance = $instance->div(2);
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new IntType(3);
        $new_instance = $instance->div(2);
        $this->assertSame(3, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(1.5, $new_instance->value());

        $instance = new FloatType(3);
        $new_instance = $instance->div(2);
        $this->assertSame(3.0, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(1.5, $new_instance->value());
    }

    /**
     * @test
     * @dataProvider divisionByZeroDataProvider
     * @covers       ::div
     */
    public function divisionByZero($instance)
    {
        $this->setExpectedException('Exception');
        $instance->div(0);
    }

    public function divisionByZeroDataProvider()
    {
        return [
            [new IntType()],
            [new FloatType()],
            [new IntType(3)],
            [new FloatType(3)],
        ];
    }

    /**
     * @test
     * @covers ::mod
     */
    public function mod()
    {
        $instance = new IntType();
        $new_instance = $instance->mod(2);
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new FloatType();
        $new_instance = $instance->mod(2);
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new IntType(3);
        $new_instance = $instance->mod(2);
        $this->assertSame(3, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(1.0, $new_instance->value());

        $instance = new FloatType(3);
        $new_instance = $instance->mod(2);
        $this->assertSame(3.0, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(1.0, $new_instance->value());
    }

    /**
     * @test
     * @covers ::exp
     */
    public function exp()
    {
        $instance = new IntType();
        $new_instance = $instance->exp(2);
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new FloatType();
        $new_instance = $instance->exp(2);
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new IntType(3);
        $new_instance = $instance->exp(2);
        $this->assertSame(3, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(9.0, $new_instance->value());

        $instance = new FloatType(3);
        $new_instance = $instance->exp(2);
        $this->assertSame(3.0, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(9.0, $new_instance->value());
    }

    /**
     * @test
     * @covers ::pow
     */
    public function pow()
    {
        $instance = new IntType();
        $new_instance = $instance->pow(2);
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new FloatType();
        $new_instance = $instance->pow(2);
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new IntType(3);
        $new_instance = $instance->pow(2);
        $this->assertSame(3, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(9.0, $new_instance->value());

        $instance = new FloatType(3);
        $new_instance = $instance->pow(2);
        $this->assertSame(3.0, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(9.0, $new_instance->value());
    }

    /**
     * @test
     * @covers ::sqrt
     */
    public function sqrt()
    {
        $instance = new IntType();
        $new_instance = $instance->sqrt();
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new FloatType();
        $new_instance = $instance->sqrt();
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new IntType(9);
        $new_instance = $instance->sqrt();
        $this->assertSame(9, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(3.0, $new_instance->value());

        $instance = new FloatType(9);
        $new_instance = $instance->sqrt();
        $this->assertSame(9.0, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(3.0, $new_instance->value());
    }

    /**
     * @test
     * @covers ::root
     */
    public function root()
    {
        $instance = new IntType();
        $new_instance = $instance->root(3);
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new FloatType();
        $new_instance = $instance->root(3);
        $this->assertSame(null, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(null, $new_instance->value());

        $instance = new IntType(27);
        $new_instance = $instance->root(3);
        $this->assertSame(27, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(3.0, $new_instance->value());

        $instance = new FloatType(27);
        $new_instance = $instance->root(3);
        $this->assertSame(27.0, $instance->value());
        $this->assertInstanceOf('\Data\Type\FloatType', $new_instance);
        $this->assertSame(3.0, $new_instance->value());
    }

    /**
     * @test
     * @covers ::eq
     */
    public function eq()
    {
        $instance = new IntType();
        $this->assertSame(false, $instance->eq(0));
        $this->assertSame(true,  $instance->eq(null));

        $instance = new IntType(1);
        $this->assertSame(false, $instance->eq(null));
        $this->assertSame(false, $instance->eq(0));
        $this->assertSame(true,  $instance->eq(1));
        $this->assertSame(false, $instance->eq(2));

        $instance = new FloatType();
        $this->assertSame(false, $instance->eq(0));
        $this->assertSame(true,  $instance->eq(null));

        $instance = new FloatType(1);
        $this->assertSame(false, $instance->eq(null));
        $this->assertSame(false, $instance->eq(0));
        $this->assertSame(true,  $instance->eq(1));
        $this->assertSame(false, $instance->eq(2));
    }

    /**
     * @test
     * @covers ::ne
     */
    public function ne()
    {
        $instance = new IntType();
        $this->assertSame(true,  $instance->ne(0));
        $this->assertSame(false, $instance->ne(null));

        $instance = new IntType(1);
        $this->assertSame(true,  $instance->ne(null));
        $this->assertSame(true,  $instance->ne(0));
        $this->assertSame(false, $instance->ne(1));
        $this->assertSame(true,  $instance->ne(2));

        $instance = new FloatType();
        $this->assertSame(true,  $instance->ne(0));
        $this->assertSame(false, $instance->ne(null));

        $instance = new FloatType(1);
        $this->assertSame(true,  $instance->ne(null));
        $this->assertSame(true,  $instance->ne(0));
        $this->assertSame(false, $instance->ne(1));
        $this->assertSame(true,  $instance->ne(2));
    }

    /**
     * @test
     * @covers ::gt
     */
    public function gt()
    {
        $instance = new IntType();
        $this->assertSame(false, $instance->gt(0));
        $this->assertSame(false, $instance->gt(null));

        $instance = new IntType(1);
        $this->assertSame(true,  $instance->gt(0));
        $this->assertSame(false, $instance->gt(1));
        $this->assertSame(false, $instance->gt(2));

        $instance = new FloatType();
        $this->assertSame(false, $instance->gt(0));
        $this->assertSame(false, $instance->gt(null));

        $instance = new FloatType(1);
        $this->assertSame(true,  $instance->gt(0));
        $this->assertSame(false, $instance->gt(1));
        $this->assertSame(false, $instance->gt(2));
    }

    /**
     * @test
     * @covers ::gte
     */
    public function gte()
    {
        $instance = new IntType();
        $this->assertSame(false, $instance->gte(0));
        $this->assertSame(true,  $instance->gte(null));

        $instance = new IntType(1);
        $this->assertSame(true,  $instance->gte(0));
        $this->assertSame(true,  $instance->gte(1));
        $this->assertSame(false, $instance->gte(2));

        $instance = new FloatType();
        $this->assertSame(false, $instance->gte(0));
        $this->assertSame(true,  $instance->gte(null));

        $instance = new FloatType(1);
        $this->assertSame(true,  $instance->gte(0));
        $this->assertSame(true,  $instance->gte(1));
        $this->assertSame(false, $instance->gte(2));
    }

    /**
     * @test
     * @covers ::lt
     */
    public function lt()
    {
        $instance = new IntType();
        $this->assertSame(false, $instance->lt(0));
        $this->assertSame(false, $instance->lt(null));

        $instance = new IntType(1);
        $this->assertSame(false, $instance->lt(0));
        $this->assertSame(false, $instance->lt(1));
        $this->assertSame(true,  $instance->lt(2));

        $instance = new FloatType();
        $this->assertSame(false, $instance->lt(0));
        $this->assertSame(false, $instance->lt(null));

        $instance = new FloatType(1);
        $this->assertSame(false, $instance->lt(0));
        $this->assertSame(false, $instance->lt(1));
        $this->assertSame(true,  $instance->lt(2));
    }

    /**
     * @test
     * @covers ::lte
     */
    public function lte()
    {
        $instance = new IntType();
        $this->assertSame(false, $instance->lte(0));
        $this->assertSame(true,  $instance->lte(null));

        $instance = new IntType(1);
        $this->assertSame(false, $instance->lte(0));
        $this->assertSame(true,  $instance->lte(1));
        $this->assertSame(true,  $instance->lte(2));

        $instance = new FloatType();
        $this->assertSame(false, $instance->lte(0));
        $this->assertSame(true,  $instance->lte(null));

        $instance = new FloatType(1);
        $this->assertSame(false, $instance->lte(0));
        $this->assertSame(true,  $instance->lte(1));
        $this->assertSame(true,  $instance->lte(2));
    }
}