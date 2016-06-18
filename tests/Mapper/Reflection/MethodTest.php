<?php

namespace Gb\Mapper\Reflection;

use PHPUnit_Framework_TestCase;
use ReflectionClass;
use Tadgola\Optional\Optional;

class MethodTest extends PHPUnit_Framework_TestCase
{
	public function setterTypeDataProvider()
	{
		return [
			['setAbsent', Optional::absent()],
			['setTypeOnParam', Optional::of('Tadgola\Optional\Optional')],
			['setIntHinted', Optional::of('integer')],
			['setStringHinted', Optional::of('string')],
			['setBooleanHinted', Optional::of('boolean')],
			['setFloatHinted', Optional::of('float')],
			['setObjectHinted', Optional::of('Optional')],
		];
	}

	/**
	 * @dataProvider setterTypeDataProvider
	 */
	public function testGetSetterTypeOnParam($method, $expected)
	{
		$object = new SettersTest();
		$rc = new ReflectionClass($object);
		$actual = Setter::of($object, $rc->getMethod($method))->getType();
		$this->assertEquals($expected, $actual);
	}
}

class SettersTest
{
	public function setAbsent($param) {}

	public function setTypeOnParam(Optional $param) {}

	/**
	 * @param integer $param
	 * @return void
	 */
	public function setIntHinted($param) {}

	/**
	 * @param string $param
	 */
	public function setStringHinted($param) {}

	/**
	 * @param boolean $param
	 */
	public function setBooleanHinted($param) {}

	/**
	 * @param float $param
	 */
	public function setFloatHinted($param) {}

	/**
	 * @param Optional $param
	 */
	public function setObjectHinted($param) {}
}