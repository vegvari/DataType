<?php

namespace Data\Type;

class StringTest extends \PHPUnit_Framework_TestCase
{
	public function testNull()
	{
		$instance = String::make();
		$this->assertSame(null, $instance->value());
	}

	public function testMake()
	{
		$instance = String::make(1);
		$this->assertSame('1', $instance->value());
	}

	public function testCast()
	{
		$data = String::cast(1);
		$this->assertSame('1', $data);
	}

	public function testCastSilent()
	{
		$data = String::castSilent(null);
		$this->assertSame(null, $data);
	}

	/**
     * @dataProvider toStringDataProvider
     */
	public function testToString($data, $expected)
	{
		$instance = String::make($data);
		$this->assertSame($expected, (string) $instance);
	}

	public function toStringDataProvider()
	{
		return array(
			array(0, '0'),
			array(1, '1'),
		);
	}

	/**
     * @dataProvider validDataProvider
     */
	public function testValid($data, $expected)
	{
		$instance = String::make($data);
		$this->assertSame($expected, $instance->value());
	}

	public function validDataProvider()
	{
		return array(
			array(Bool::make(1),            '1'),
			array(Float::make(1),           '1'),
			array(Int::make(1),             '1'),
			array(String::make(1),          '1'),
			array(String::make(0),          '0'),
			array(false,                    '0'),
			array(true,                     '1'),
			array(0.0,                      '0'),
			array(1.0,                      '1'),
			array(0,                        '0'),
			array(1,                        '1'),
			array('0',                      '0'),
			array('1',                      '1'),
			array(2.0,                      '2'),
			array(2,                        '2'),
			array('2',                      '2'),
			array('árvíztűrő tükörfúrógép', 'árvíztűrő tükörfúrógép'),
		);
	}

	/**
     * @dataProvider invalidDataProvider
     */
	public function testInvalid($data, $expected)
	{
		$this->setExpectedException($expected);
		$instance = String::make($data);
	}

	public function invalidDataProvider()
	{
		return array(
			array(array(),              '\InvalidArgumentException'),
			array(new \stdClass(),      '\InvalidArgumentException'),
			array(fopen(__FILE__, 'r'), '\InvalidArgumentException'),
		);
	}

	public function testLength()
	{
		$instance = String::make('árvíztűrő tükörfúrógép');
		$this->assertSame(22, $instance->length());
	}

	public function testSubstr()
	{
		$instance = String::make('árvíztűrő tükörfúrógép');
		$this->assertTrue($instance->substr(0) instanceof String);
		$this->assertSame('árvíztűrő tükörfúrógép', $instance->substr(0)->value());
	}

	public function testSubstrMissingFrom()
	{
		$this->setExpectedException('PHPUnit_Framework_Error_Warning');
		$instance = String::make('árvíztűrő tükörfúrógép');
		$instance->substr();
	}

	public function testSubstrInvalidLength()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = String::make('árvíztűrő tükörfúrógép');
		$instance->substr(0, 'test');
	}

	public function testSubstrOutOfRangeFrom()
	{
		$this->setExpectedException('\LengthException');
		$instance = String::make('árvíztűrő tükörfúrógép');
		$instance->substr($instance->length() + 1);
	}

	public function testSubstrOutOfRangeLength()
	{
		$this->setExpectedException('\LengthException');
		$instance = String::make('árvíztűrő tükörfúrógép');
		$instance->substr(0, $instance->length() + 1);
	}

	public function testToLower()
	{
		$instance = String::make('ÁRVÍZTŰRŐ TÜKÖRFÚRÓGÉP');
		$this->assertTrue($instance->toLower() instanceof String);
		$this->assertSame('árvíztűrő tükörfúrógép', $instance->toLower()->value());
	}

	public function testToUpper()
	{
		$instance = String::make('árvíztűrő tükörfúrógép');
		$this->assertTrue($instance->toUpper() instanceof String);
		$this->assertSame('ÁRVÍZTŰRŐ TÜKÖRFÚRÓGÉP', $instance->toUpper()->value());
	}

	public function testUpperFirst()
	{
		$instance = String::make('árvíztűrő tükörfúrógép');
		$this->assertTrue($instance->upperFirst() instanceof String);
		$this->assertSame('Árvíztűrő tükörfúrógép', $instance->upperFirst()->value());
	}

	public function testUpperWords()
	{
		$instance = String::make('árvíztűrő tükörfúrógép');
		$this->assertTrue($instance->upperWords() instanceof String);
		$this->assertSame('Árvíztűrő Tükörfúrógép', $instance->upperWords()->value());
	}

	public function testArrayAccess()
	{
		$instance = String::make('árvíztűrő tükörfúrógép');

		// isset
		for($n = 0; $n < $instance->length(); $n++)
		{
			$this->assertSame(true, isset($instance[$n]));
		}

		// isset invalid offset
		$this->assertSame(false, isset($instance[-1]));
		$this->assertSame(false, isset($instance[$instance->length() + 1]));
		$this->assertSame(false, isset($instance[null]));

		// get
		$this->assertEquals(String::make('á'), $instance[0]);
		$this->assertEquals(String::make('r'), $instance[1]);
		$this->assertEquals(String::make('v'), $instance[2]);
		$this->assertEquals(String::make('í'), $instance[3]);
		$this->assertEquals(String::make('z'), $instance[4]);
		$this->assertEquals(String::make('t'), $instance[5]);
		$this->assertEquals(String::make('ű'), $instance[6]);
		$this->assertEquals(String::make('r'), $instance[7]);
		$this->assertEquals(String::make('ő'), $instance[8]);
		$this->assertEquals(String::make(' '), $instance[9]);
		$this->assertEquals(String::make('t'), $instance[10]);
		$this->assertEquals(String::make('ü'), $instance[11]);
		$this->assertEquals(String::make('k'), $instance[12]);
		$this->assertEquals(String::make('ö'), $instance[13]);
		$this->assertEquals(String::make('r'), $instance[14]);
		$this->assertEquals(String::make('f'), $instance[15]);
		$this->assertEquals(String::make('ú'), $instance[16]);
		$this->assertEquals(String::make('r'), $instance[17]);
		$this->assertEquals(String::make('ó'), $instance[18]);
		$this->assertEquals(String::make('g'), $instance[19]);
		$this->assertEquals(String::make('é'), $instance[20]);
		$this->assertEquals(String::make('p'), $instance[21]);
	}

	public function testArrayAccessInvalidOffset1()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = String::make('árvíztűrő tükörfúrógép');
		$test = $instance[-1];
	}

	public function testArrayAccessInvalidOffset2()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = String::make('árvíztűrő tükörfúrógép');
		$test = $instance[$instance->length()];
	}

	public function testArrayAccessInvalidOffset3()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = String::make('árvíztűrő tükörfúrógép');
		$test = $instance[null];
	}

	public function testIterator()
	{
		$instance = String::make('𐆖 árvíztűrő tükörfúrógép 𐆖');

		$n = 0;
		foreach ($instance as $key => $value) {
			$this->assertSame($n, $key);
			$n++;

			$this->assertEquals($instance->substr($key, 1), $value);
		}
	}
}
