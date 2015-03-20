<?php

namespace Data\Type;

class StringTest extends \PHPUnit_Framework_TestCase
{
	public function testNull()
	{
		$instance = String::create();
		$this->assertSame(null, $instance->value());
	}

	public function testMake()
	{
		$instance = String::create(1);
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
		$instance = String::create($data);
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
		$instance = String::create($data);
		$this->assertSame($expected, $instance->value());
	}

	public function validDataProvider()
	{
		return array(
			array(Bool::create(1),            '1'),
			array(Float::create(1),           '1'),
			array(Int::create(1),             '1'),
			array(String::create(1),          '1'),
			array(String::create(0),          '0'),
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
		$instance = String::create($data);
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
		$instance = String::create('árvíztűrő tükörfúrógép', 'UTF-8');
		$this->assertSame(22, $instance->length());
	}

	public function testSubstr()
	{
		$instance = String::create('árvíztűrő tükörfúrógép', 'UTF-8');
		$this->assertSame('árvíztűrő tükörfúrógép', $instance->substr(0));
	}

	public function testSubstrMissingFrom()
	{
		$this->setExpectedException('PHPUnit_Framework_Error_Warning');
		$instance = String::create('árvíztűrő tükörfúrógép');
		$instance->substr();
	}

	public function testSubstrInvalidLength()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = String::create('árvíztűrő tükörfúrógép');
		$instance->substr(0, 'test');
	}

	public function testSubstrOutOfRangeFrom()
	{
		$this->setExpectedException('\LengthException');
		$instance = String::create('árvíztűrő tükörfúrógép');
		$instance->substr($instance->length() + 1);
	}

	public function testSubstrOutOfRangeLength()
	{
		$this->setExpectedException('\LengthException');
		$instance = String::create('árvíztűrő tükörfúrógép');
		$instance->substr(0, $instance->length() + 1);
	}

	public function testToLower()
	{
		$instance = String::create('ÁRVÍZTŰRŐ TÜKÖRFÚRÓGÉP', 'UTF-8');
		$this->assertTrue($instance->toLower() instanceof String);
		$this->assertSame('árvíztűrő tükörfúrógép', $instance->toLower()->value());
	}

	public function testToUpper()
	{
		$instance = String::create('árvíztűrő tükörfúrógép', 'UTF-8');
		$this->assertTrue($instance->toUpper() instanceof String);
		$this->assertSame('ÁRVÍZTŰRŐ TÜKÖRFÚRÓGÉP', $instance->toUpper()->value());
	}

	public function testUpperFirst()
	{
		$instance = String::create('árvíztűrő tükörfúrógép', 'UTF-8');
		$this->assertTrue($instance->upperFirst() instanceof String);
		$this->assertSame('Árvíztűrő tükörfúrógép', $instance->upperFirst()->value());
	}

	public function testUpperWords()
	{
		$instance = String::create('árvíztűrő tükörfúrógép', 'UTF-8');
		$this->assertTrue($instance->upperWords() instanceof String);
		$this->assertSame('Árvíztűrő Tükörfúrógép', $instance->upperWords()->value());
	}

	public function testArrayAccess()
	{
		$instance = String::create('árvíztűrő tükörfúrógép', 'UTF-8');

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

	public function testArrayAccessInvalidOffset1()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = String::create('árvíztűrő tükörfúrógép');
		$test = $instance[-1];
	}

	public function testArrayAccessInvalidOffset2()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = String::create('árvíztűrő tükörfúrógép');
		$test = $instance[$instance->length()];
	}

	public function testArrayAccessInvalidOffset3()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$instance = String::create('árvíztűrő tükörfúrógép');
		$test = $instance[null];
	}

	public function testIterator()
	{
		$instance = String::create('𐆖 árvíztűrő tükörfúrógép 𐆖');

		$n = 0;
		foreach ($instance as $key => $value) {
			$this->assertSame($n, $key);
			$n++;

			$this->assertEquals($instance->substr($key, 1), $value);
		}
	}
}
