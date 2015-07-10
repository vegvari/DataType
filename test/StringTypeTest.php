<?php

use Data\Type\Cast;
use Data\Type\IntType;
use Data\Type\BoolType;
use Data\Type\TimeType;
use Data\Type\FloatType;
use Data\Type\StringType;

/**
 * @coversDefaultClass \Data\Type\StringType
 */
class StringTypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers ::__toString
     */
    public function toString()
    {
        $instance = new StringType();
        $this->assertSame('', (string) $instance);

        $instance = new StringType('1');
        $this->assertSame('1', (string) $instance);

        $instance = new StringType('0');
        $this->assertSame('0', (string) $instance);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::setEncoding
     * @covers ::getEncoding
     */
    public function setEncoding()
    {
        $instance = new StringType();
        $this->assertSame(mb_internal_encoding(), $instance->getEncoding());

        $instance = new StringType(null, 'pass');
        $this->assertSame('pass', $instance->getEncoding());
    }

    /**
     * @test
     * @runInSeparateProcess
     * @afterClass
     * @covers ::supportedEncodings
     */
    public function supportedEncodings()
    {
        $encodings = StringType::supportedEncodings();
        $this->assertSame(true, is_array($encodings));

        $supported = mb_list_encodings();
        foreach ($supported as $key => $value) {
            $this->assertContains($value, $encodings);

            $aliases = mb_encoding_aliases($value);
            foreach ($aliases as $k => $v) {
                $this->assertArrayHasKey(strtolower($v), $encodings);
            }
        }
    }

    /**
     * @test
     * @covers ::isEncodingSupported
     */
    public function isEncodingSupported()
    {
        $supported = mb_list_encodings();
        foreach ($supported as $key => $value) {
            $this->assertSame(true, StringType::isEncodingSupported($value));

            $aliases = mb_encoding_aliases($value);
            foreach ($aliases as $k => $v) {
                $this->assertSame(true, StringType::isEncodingSupported($v));
            }
        }

        $this->assertSame(false, StringType::isEncodingSupported('This encoding is invalid'));
    }

    /**
     * @test
     * @covers ::getRealEncoding
     */
    public function getRealEncoding()
    {
        $supported = mb_list_encodings();
        foreach ($supported as $key => $value) {
            $this->assertSame($value, StringType::getRealEncoding($value));

            $aliases = mb_encoding_aliases($value);
            foreach ($aliases as $k => $v) {
                $this->assertSame($value, StringType::getRealEncoding($v));
            }
        }
    }

    /**
     * @test
     * @covers ::getRealEncoding
     */
    public function getRealEncodingFail()
    {
        $this->setExpectedException('Exception');
        StringType::getRealEncoding('This encoding is invalid');
    }

    /**
     * @test
     * @dataProvider checkDataProvider
     * @covers       ::check
     * @covers       ::value
     */
    public function check($data, $expected)
    {
        $instance = new StringType($data);
        $this->assertSame($expected, $instance->value());
    }

    public function checkDataProvider()
    {
        return [
            [null,                          null],
            ['',                            null],
            [new BoolType(),                null],
            [new FloatType(),               null],
            [new IntType(),                 null],
            [new StringType(),              null],

            [true,                          '1'],
            [1.0,                           '1'],
            [1,                             '1'],
            ['1',                           '1'],
            [new BoolType(true),            '1'],
            [new FloatType(1),              '1'],
            [new IntType(1),                '1'],
            [new StringType(1),             '1'],

            [false,                         '0'],
            [0.0,                           '0'],
            [0,                             '0'],
            ['0',                           '0'],
            [new BoolType(false),           '0'],
            [new FloatType(0),              '0'],
            [new IntType(0),                '0'],
            [new StringType(0),             '0'],

            [2.0,                           '2'],
            [2,                             '2'],
            ['2',                           '2'],
            ['árvíztűrő tükörfúrógép',      'árvíztűrő tükörfúrógép'],
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
        $instance = new StringType($data);
    }

    public function checkFailDataProvider()
    {
        return [
            [[],                   '\Data\Type\Exceptions\InvalidStringException'],
            [new stdClass(),       '\Data\Type\Exceptions\InvalidStringException'],
            [fopen(__FILE__, 'r'), '\Data\Type\Exceptions\InvalidStringException'],
        ];
    }

    /**
     * @test
     * @covers ::value
     */
    public function value()
    {
        $instance = new StringType('árvíztűrő tükörfúrógép', 'UTF-8');
        $this->assertSame(mb_convert_encoding('árvíztűrő tükörfúrógép', 'ISO-8859-2'), $instance->value('ISO-8859-2'));
    }

    /**
     * @test
     * @covers ::length
     */
    public function length()
    {
        $instance = new StringType('árvíztűrő tükörfúrógép', 'UTF-8');
        $this->assertSame(22, $instance->length());

        $instance = new StringType(null, 'UTF-8');
        $this->assertSame(0, $instance->length());
    }

    /**
     * @test
     * @covers ::substr
     */
    public function substr()
    {
        $instance = new StringType('árvíztűrő tükörfúrógép', 'UTF-8');
        $this->assertSame('árvíztűrő tükörfúrógép', $instance->substr(0));
        $this->assertSame('r', $instance->substr(1, 1));
        $this->assertSame('v', $instance->substr(2, 1));
        $this->assertSame('í', $instance->substr(3, 1));
        $this->assertSame('z', $instance->substr(4, 1));
        $this->assertSame('t', $instance->substr(5, 1));
        $this->assertSame('ű', $instance->substr(6, 1));
        $this->assertSame('r', $instance->substr(7, 1));
        $this->assertSame('ő', $instance->substr(8, 1));
        $this->assertSame('tükör', $instance->substr(10, 5));
        $this->assertSame('fúrógép', $instance->substr(15));
    }

    /**
     * @test
     * @covers ::substr
     */
    public function substrFromFail()
    {
        $this->setExpectedException('LengthException');
        $instance = new StringType('árvíztűrő tükörfúrógép');
        $instance->substr($instance->length() + 1);
    }

    /**
     * @test
     * @covers ::substr
     */
    public function substrLengthFail()
    {
        $this->setExpectedException('LengthException');
        $instance = new StringType('árvíztűrő tükörfúrógép');
        $instance->substr(0, $instance->length() + 1);
    }

    /**
     * @test
     * @covers ::toLower
     */
    public function toLower()
    {
        $instance = new StringType('ÁRVÍZTŰRŐ TÜKÖRFÚRÓGÉP', 'UTF-8');
        $this->assertInstanceOf('\Data\Type\StringType', $instance->toLower());
        $this->assertSame('árvíztűrő tükörfúrógép', $instance->toLower()->value());
    }

    /**
     * @test
     * @covers ::toUpper
     */
    public function toUpper()
    {
        $instance = new StringType('árvíztűrő tükörfúrógép', 'UTF-8');
        $this->assertInstanceOf('\Data\Type\StringType', $instance->toUpper());
        $this->assertSame('ÁRVÍZTŰRŐ TÜKÖRFÚRÓGÉP', $instance->toUpper()->value());
    }

    /**
     * @test
     * @covers ::upperFirst
     */
    public function upperFirst()
    {
        $instance = new StringType('árvíztűrő tükörfúrógép', 'UTF-8');
        $this->assertInstanceOf('\Data\Type\StringType', $instance->upperFirst());
        $this->assertSame('Árvíztűrő tükörfúrógép', $instance->upperFirst()->value());
    }

    /**
     * @test
     * @covers ::upperWords
     */
    public function upperWords()
    {
        $instance = new StringType('árvíztűrő tükörfúrógép', 'UTF-8');
        $this->assertInstanceOf('\Data\Type\StringType', $instance->upperWords());
        $this->assertSame('Árvíztűrő Tükörfúrógép', $instance->upperWords()->value());
    }

    /**
     * @test
     * @covers ::offsetExists
     */
    public function offsetExists()
    {
        $instance = new StringType('árvíztűrő tükörfúrógép', 'UTF-8');
        for ($n = 0; $n < $instance->length(); $n++) {
            $this->assertSame(true, isset ($instance[$n]));
        }
    }

    /**
     * @test
     * @covers ::offsetExists
     */
    public function offsetExistsMinFail()
    {
        $this->setExpectedException('InvalidArgumentException');
        $instance = new StringType(null, 'UTF-8');
        isset ($instance[0]);
    }

    /**
     * @test
     * @covers ::offsetExists
     */
    public function offsetExistsMaxFail()
    {
        $this->setExpectedException('InvalidArgumentException');
        $instance = new StringType('árvíztűrő tükörfúrógép', 'UTF-8');
        isset ($instance[$instance->length() + 1]);
    }

    /**
     * @test
     * @covers ::offsetGet
     */
    public function offsetGet()
    {
        $instance = new StringType('árvíztűrő tükörfúrógép', 'UTF-8');
        $this->assertEquals('á', $instance[0]);
        $this->assertEquals('r', $instance[1]);
        $this->assertEquals('v', $instance[2]);
        $this->assertEquals('í', $instance[3]);
        $this->assertEquals('z', $instance[4]);
        $this->assertEquals('t', $instance[5]);
        $this->assertEquals('ű', $instance[6]);
        $this->assertEquals('r', $instance[7]);
        $this->assertEquals('ő', $instance[8]);
        $this->assertEquals(' ', $instance[9]);
        $this->assertEquals('t', $instance[10]);
        $this->assertEquals('ü', $instance[11]);
        $this->assertEquals('k', $instance[12]);
        $this->assertEquals('ö', $instance[13]);
        $this->assertEquals('r', $instance[14]);
        $this->assertEquals('f', $instance[15]);
        $this->assertEquals('ú', $instance[16]);
        $this->assertEquals('r', $instance[17]);
        $this->assertEquals('ó', $instance[18]);
        $this->assertEquals('g', $instance[19]);
        $this->assertEquals('é', $instance[20]);
        $this->assertEquals('p', $instance[21]);
    }

    /**
     * @test
     * @covers ::offsetSet
     */
    public function offsetSet()
    {
        $instance = new StringType('árvíztűrő tükörfúrógép', 'UTF-8');

        $instance[0] = 0;
        $this->assertSame('0rvíztűrő tükörfúrógép', $instance->value());

        $instance[0] = 'TEST';
        $this->assertSame('TESTrvíztűrő tükörfúrógép', $instance->value());

        $instance[0] = '';
        $this->assertSame('ESTrvíztűrő tükörfúrógép', $instance->value());

        $instance[0] = null;
        $instance[0] = null;
        $this->assertSame('Trvíztűrő tükörfúrógép', $instance->value());

        $instance[1] = 'ES';
        $instance[3] = 'T';
        $this->assertSame('TESTíztűrő tükörfúrógép', $instance->value());
    }

    /**
     * @test
     * @covers ::offsetUnset
     */
    public function offsetUnset()
    {
        $instance = new StringType('árvíztűrő tükörfúrógép', 'UTF-8');

        unset ($instance[0]);
        $this->assertSame('rvíztűrő tükörfúrógép', $instance->value());

        unset ($instance[0]);
        unset ($instance[0]);
        unset ($instance[0]);
        $this->assertSame('ztűrő tükörfúrógép', $instance->value());

        unset ($instance[1]);
        $this->assertSame('zűrő tükörfúrógép', $instance->value());
    }

    /**
     * @test
     * @covers ::rewind
     * @covers ::current
     * @covers ::key
     * @covers ::next
     * @covers ::valid
     */
    public function iterator()
    {
        $instance = new StringType('árvíztűrő tükörfúrógép');

        $n = 0;
        foreach ($instance as $key => $value) {
            $this->assertSame($n, $key);
            $this->assertSame($instance->substr($key, 1), $value);
            $n++;
        }
    }

    /**
     * @covers ::count
     */
    public function testCount()
    {
        $instance = new StringType('árvíztűrő tükörfúrógép');
        $this->assertSame(22, count($instance));
    }
}
