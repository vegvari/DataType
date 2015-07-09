<?php

use Data\Type\Cast;
use Data\Type\IntType;
use Data\Type\BoolType;
use Data\Type\TimeType;
use Data\Type\FloatType;
use Data\Type\StringType;

/**
 * @coversDefaultClass \Data\Type\Cast
 */
class CastTest extends PHPUnit_Framework_TestCase
{
	public function testBool()
	{
		$this->assertSame(true, Cast::Bool('1'));
	}

	public function testBoolWithNull()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::Bool();
	}

	public function testNullableBool()
	{
		$this->assertSame(null, Cast::_Bool());
	}





	// signed float, test with zero
	public function testSignedFloatWithZero()
	{
		$this->assertSame(0.0, Cast::Float('0'));
	}

	// signed float, test with positive
	public function testSignedFloatWithPositive()
	{
		$this->assertSame(1.0, Cast::Float('1'));
	}

	// signed float, test with negative
	public function testSignedFloatWithNegative()
	{
		$this->assertSame(-1.0, Cast::Float('-1'));
	}

	// signed float, test with null
	public function testSignedFloatWithNull()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::Float();
	}

	// nullable signed float, test with zero
	public function testNullableSignedFloatWithZero()
	{
		$this->assertSame(0.0, Cast::_Float('0'));
	}

	// nullable signed float, test with positive
	public function testNullableSignedFloatWithPositive()
	{
		$this->assertSame(1.0, Cast::_Float('1'));
	}

	// nullable signed float, test with negative
	public function testNullableSignedFloatWithNegative()
	{
		$this->assertSame(-1.0, Cast::_Float('-1'));
	}

	// nullable signed float, test with null
	public function testNullableSignedFloatWithNull()
	{
		$this->assertSame(null, Cast::_Float());
	}





	// unsigned float, test with zero
	public function testUnsignedFloatWithZero()
	{
		$this->assertSame(0.0, Cast::uFloat('0'));
	}

	// unsigned float, test with positive
	public function testUnsignedFloatWithPositive()
	{
		$this->assertSame(1.0, Cast::uFloat('1'));
	}

	// unsigned float, test with negative
	public function testUnsignedFloatWithNegative()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::uFloat(-1);
	}

	// unsigned float, test with null
	public function testUnsignedFloatWithNull()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::uFloat();
	}

	// nullable unsigned float, test with zero
	public function testNullableUnsignedFloatWithZero()
	{
		$this->assertSame(0.0, Cast::_uFloat('0'));
	}

	// nullable unsigned float, test with positive
	public function testNullableUnsignedFloatWithPositive()
	{
		$this->assertSame(1.0, Cast::_uFloat('1'));
	}

	// nullable unsigned float, test with negative
	public function testNullableUnsignedFloatWithNegative()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::_uFloat(-1);
	}

	// nullable unsigned float, test with null
	public function testNullableUnsignedFloatWithNull()
	{
		$this->assertSame(null, Cast::_uFloat());
	}





	// positive float, test with zero
	public function testPositiveFloatWithZero()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::pFloat(0);
	}

	// positive float, test with positive
	public function testPositiveFloatWithPositive()
	{
		$this->assertSame(1.0, Cast::pFloat('1'));
	}

	// positive float, test with negative
	public function testPositiveFloatWithNegative()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::pFloat(-1);
	}

	// positive float, test with null
	public function testPositiveFloatWithNull()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::pFloat();
	}

	// nullable positive float, test with zero
	public function testNullablePositiveFloatWithZero()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::_pFloat(0);
	}

	// nullable positive float, test with positive
	public function testNullablePositiveFloatWithPositive()
	{
		$this->assertSame(1.0, Cast::_pFloat('1'));
	}

	// nullable positive float, test with negative
	public function testNullablePositiveFloatWithNegative()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::_pFloat(-1);
	}

	// nullable positive float, test with null
	public function testNullablePositiveFloatWithNull()
	{
		$this->assertSame(null, Cast::_pFloat());
	}





	// negative float, test with zero
	public function testNegativeFloatWithZero()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::nFloat(0);
	}

	// negative float, test with positive
	public function testNegativeFloatWithPositive()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::nFloat(1);
	}

	// negative float, test with negative
	public function testNegativeFloatWithNegative()
	{
		$this->assertSame(-1.0, Cast::nFloat('-1'));
	}

	// negative float, test with null
	public function testNegativeFloatNotNull()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::nFloat();
	}

	// nullable negative float, test with zero
	public function testNullableNegativeFloatWithZero()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::_nFloat(0);
	}

	// nullable negative float, test with positive
	public function testNullableNegativeFloatWithPositive()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::_nFloat(1);
	}

	// nullable negative float, test with negative
	public function testNullableNegativeFloatWithNegative()
	{
		$this->assertSame(-1.0, Cast::_nFloat('-1'));
	}

	// nullable negative float, test with null
	public function testNullableNegativeFloatNotNull()
	{
		$this->assertSame(null, Cast::_nFloat());
	}





	// signed int, test with zero
	public function testSignedIntWithZero()
	{
		$this->assertSame(0, Cast::Int('0'));
	}

	// signed int, test with positive
	public function testSignedIntWithPositive()
	{
		$this->assertSame(1, Cast::Int('1'));
	}

	// signed int, test with negative
	public function testSignedIntWithNegative()
	{
		$this->assertSame(-1, Cast::Int('-1'));
	}

	// signed int, test with null
	public function testSignedIntWithNull()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::Int();
	}

	// nullable signed int, test with zero
	public function testNullableSignedIntWithZero()
	{
		$this->assertSame(0, Cast::_Int('0'));
	}

	// nullable signed int, test with positive
	public function testNullableSignedIntWithPositive()
	{
		$this->assertSame(1, Cast::_Int('1'));
	}

	// nullable signed int, test with negative
	public function testNullableSignedIntWithNegative()
	{
		$this->assertSame(-1, Cast::_Int('-1'));
	}

	// nullable signed int, test with null
	public function testNullableSignedIntWithNull()
	{
		$this->assertSame(null, Cast::_Int());
	}





	// unsigned int, test with zero
	public function testUnsignedIntWithZero()
	{
		$this->assertSame(0, Cast::uInt('0'));
	}

	// unsigned int, test with positive
	public function testUnsignedIntWithPositive()
	{
		$this->assertSame(1, Cast::uInt('1'));
	}

	// unsigned int, test with negative
	public function testUnsignedIntWithNegative()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::uInt(-1);
	}

	// unsigned int, test with null
	public function testUnsignedIntWithNull()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::uInt();
	}

	// nullable unsigned int, test with zero
	public function testNullableUnsignedIntWithZero()
	{
		$this->assertSame(0, Cast::_uInt('0'));
	}

	// nullable unsigned int, test with positive
	public function testNullableUnsignedIntWithPositive()
	{
		$this->assertSame(1, Cast::_uInt('1'));
	}

	// nullable unsigned int, test with negative
	public function testNullableUnsignedIntWithNegative()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::_uInt(-1);
	}

	// nullable unsigned int, test with null
	public function testNullableUnsignedIntWithNull()
	{
		$this->assertSame(null, Cast::_uInt());
	}





	// positive int, test with zero
	public function testPositiveIntWithZero()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::pInt(0);
	}

	// positive int, test with positive
	public function testPositiveIntWithPositive()
	{
		$this->assertSame(1, Cast::pInt('1'));
	}

	// positive int, test with negative
	public function testPositiveIntWithNegative()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::pInt(-1);
	}

	// positive int, test with null
	public function testPositiveIntWithNull()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::pInt();
	}

	// nullable positive int, test with zero
	public function testNullablePositiveIntWithZero()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::_pInt(0);
	}

	// nullable positive int, test with positive
	public function testNullablePositiveIntWithPositive()
	{
		$this->assertSame(1, Cast::_pInt('1'));
	}

	// nullable positive int, test with negative
	public function testNullablePositiveIntWithNegative()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::_pInt(-1);
	}

	// nullable positive int, test with null
	public function testNullablePositiveIntWithNull()
	{
		$this->assertSame(null, Cast::_pInt());
	}





	// negative int, test with zero
	public function testNegativeIntWithZero()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::nInt(0);
	}

	// negative int, test with positive
	public function testNegativeIntWithPositive()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::nInt(1);
	}

	// negative int, test with negative
	public function testNegativeIntWithNegative()
	{
		$this->assertSame(-1, Cast::nInt('-1'));
	}

	// negative int, test with null
	public function testNegativeIntNotNull()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::nInt();
	}

	// nullable negative int, test with zero
	public function testNullableNegativeIntWithZero()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::_nInt(0);
	}

	// nullable negative int, test with positive
	public function testNullableNegativeIntWithPositive()
	{
		$this->setExpectedException('InvalidArgumentException');
		Cast::_nInt(1);
	}

	// nullable negative int, test with negative
	public function testNullableNegativeIntWithNegative()
	{
		$this->assertSame(-1, Cast::_nInt('-1'));
	}

	// nullable negative int, test with null
	public function testNullableNegativeIntNotNull()
	{
		$this->assertSame(null, Cast::_nInt());
	}





	public function testString()
	{
		$data = Cast::String('1');
		$this->assertSame('1', $data);
	}

	public function testStringWithNull()
	{
		$this->setExpectedException('InvalidArgumentException');
		$data = Cast::String();
	}

	public function testNullableString()
	{
		$data = Cast::_String();
		$this->assertSame(null, $data);
	}
}
