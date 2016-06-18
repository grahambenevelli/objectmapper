<?php

namespace Gb\Mapper\Reflection;

use PHPUnit_Framework_TestCase;
use ReflectionClass;
use Tadgola\Optional\Optional;

class PropertyTest extends PHPUnit_Framework_TestCase
{
	public function setterTypeDataProvider()
	{
		return [
			['publicNoComment', Optional::absent()],
			['publicInteger', Optional::of('integer')],
			['publicString', Optional::of('string')],
			['publicObject', Optional::of('Optional')],
			['publicObjectFullPath', Optional::of('\Tadgola\Optional\Optional')],
			['protectedInteger', Optional::of('integer')],
			['privateInteger', Optional::of('integer')],
			['publicIntegerSecondAnnotation', Optional::of('integer')]
		];
	}

	/**
	 * @dataProvider setterTypeDataProvider
	 */
	public function testGetSetterTypeOnParam($name, $expected)
	{
		$rc = new ReflectionClass(new PropertyTestExample());
		$property = Property::of($rc->getProperty($name));
		$this->assertEquals($expected, $property->getType());
	}
}

class PropertyTestExample
{
	public $publicNoComment;

	/**
	 * @var integer
	 */
	public $publicInteger;

	/**
	 * @other something else
	 * @var integer
	 */
	public $publicIntegerSecondAnnotation;

	/**
	 * @var string
	 */
	public $publicString;

	/**
	 * @var Optional
	 */
	public $publicObject;

	/**
	 * @var \Tadgola\Optional\Optional
	 */
	public $publicObjectFullPath;

	/**
	 * @var integer
	 */
	protected $protectedInteger;

	/**
	 * @var integer
	 */
	private $privateInteger;
}