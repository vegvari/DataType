<?php

use Data\Type\Cast;
use Data\Type\IntType;
use Data\Type\BoolType;
use Data\Type\FloatType;
use Data\Type\StringType;
use Data\Type\DateTimeType;

/**
 * @coversDefaultClass \Data\Type\Cast
 */
class CastTest extends PHPUnit_Framework_TestCase
{
    public function testBoolNotNull()
    {
        $this->assertSame(true, Cast::Bool('1'));
        $this->assertSame(false, Cast::Bool('0'));
    }

    public function testBoolNotNullFail()
    {
        $this->setExpectedException('InvalidArgumentException');
        Cast::Bool();
    }

    public function testBoolNull()
    {
        $this->assertSame(null, Cast::_Bool());
    }

    public function testFloat()
    {
        $this->assertSame(-1.0, Cast::Float(-1));
        $this->assertSame(0.0, Cast::Float(0));
        $this->assertSame(1.0, Cast::Float(1));
    }

    public function testFloatRange()
    {
        $this->assertSame(-1.0, Cast::Float(-1, -1));
        $this->assertSame(0.0, Cast::Float(0, 0, 0));
        $this->assertSame(1.0, Cast::Float(1, null, 1));
    }

    /**
     * @dataProvider testFloatRangeFailProvider
     */
    public function testFloatRangeFail(array $data)
    {
        $this->setExpectedException('InvalidArgumentException');
        Cast::Float($data[0], $data[1], $data[2]);
    }

    public function testFloatRangeFailProvider()
    {
        return [
            [[-1, 2, null]],
            [[0, 2, null]],
            [[1, 2, null]],

            [[-1, null, -2]],
            [[0, null, -2]],
            [[1, null, -2]],

            [[-1, 1, -2]],
            [[0, 1, -2]],
            [[1, 1, -2]],
        ];
    }

    public function testUnsignedFloat()
    {
        $this->assertSame(0.0, Cast::uFloat(0));
    }

    public function testPositiveFloat()
    {
        $this->assertSame(1.0, Cast::pFloat(1));
    }

    public function testNegativeFloat()
    {
        $this->assertSame(-1.0, Cast::nFloat(-1));
    }

    public function testUnsignedFloatFail()
    {
        $this->setExpectedException('InvalidArgumentException');
        Cast::uFloat(-1);
    }

    public function testPositiveFloatFail()
    {
        $this->setExpectedException('InvalidArgumentException');
        Cast::nFloat(0);
    }

    public function testNegativeFloatFail()
    {
        $this->setExpectedException('InvalidArgumentException');
        Cast::pFloat(0);
    }

    public function testInt()
    {
        $this->assertSame(-1, Cast::Int(-1));
        $this->assertSame(0, Cast::Int(0));
        $this->assertSame(1, Cast::Int(1));
    }

    public function testIntRange()
    {
        $this->assertSame(-1, Cast::Int(-1, -1));
        $this->assertSame(0, Cast::Int(0, 0, 0));
        $this->assertSame(1, Cast::Int(1, null, 1));
    }

    /**
     * @dataProvider testFloatRangeFailProvider
     */
    public function testIntRangeFail(array $data)
    {
        $this->setExpectedException('InvalidArgumentException');
        Cast::Int($data[0], $data[1], $data[2]);
    }

    public function testUnsignedInt()
    {
        $this->assertSame(0, Cast::uInt(0));
    }

    public function testPositiveInt()
    {
        $this->assertSame(1, Cast::pInt(1));
    }

    public function testNegativeInt()
    {
        $this->assertSame(-1, Cast::nInt(-1));
    }

    public function testUnsignedIntFail()
    {
        $this->setExpectedException('InvalidArgumentException');
        Cast::uInt(-1);
    }

    public function testPositiveIntFail()
    {
        $this->setExpectedException('InvalidArgumentException');
        Cast::nInt(0);
    }

    public function testNegativeIntFail()
    {
        $this->setExpectedException('InvalidArgumentException');
        Cast::pInt(0);
    }

    public function testStringNotNull()
    {
        $this->assertSame('1', Cast::String('1'));
        $this->assertSame(mb_convert_encoding('árvíztűrő tükörfúrógép', 'ISO-8859-2'), Cast::String(mb_convert_encoding('árvíztűrő tükörfúrógép', 'ISO-8859-2'), 'ISO-8859-2'));
    }

    public function testStringNotNullFail()
    {
        $this->setExpectedException('InvalidArgumentException');
        Cast::String();
    }

    public function testStringNull()
    {
        $this->assertSame(null, Cast::_String());
    }

    public function testDateTimeNotNull()
    {
        $this->assertSame('2012-01-01 00:00:00', Cast::DateTime('2012-01-01 00:00:00'));

        $dt = new DateTime('now', new DateTimeZone('UTC'));
        $this->assertSame($dt->format('Y-m-d H:i:s'), Cast::DateTime('now', 'UTC'));
    }

    public function testDateTimeNotNullFail()
    {
        $this->setExpectedException('InvalidArgumentException');
        Cast::DateTime();
    }

    public function testDateTimeNull()
    {
        $this->assertSame(null, Cast::_DateTime());
    }

    public function testUnknownType()
    {
        $this->setExpectedException('Exception');
        Cast::qwerty();
    }
}
