<?php

namespace Gb\Mapper\Reflection;

use ReflectionProperty;
use Tadgola\Collect\EloquentIterable;
use Tadgola\Optional\Optional;

/**
 * Class Property
 * @package Gb\Mapper\Reflection
 */
class Property extends Commentable
{

	/**
	 * @var ReflectionProperty
	 */
	private $property;

	/**
	 * Property constructor.
	 * @param ReflectionProperty $property
	 */
	private function __construct(ReflectionProperty $property)
	{
		$this->property = $property;
	}

	/**
	 * Get the Property of a given ReflectionProperty
	 *
	 * @param ReflectionProperty $property
	 * @return Property
	 */
	public static function of(ReflectionProperty $property)
	{
		return new self($property);
	}

	/**
	 * Get the type of the property on the method
	 *
	 * @return Optional
	 */
	public function getType()
	{
		$annotations = $this->parseAnnotations();
		return EloquentIterable::wrap($annotations)
			->filter(function ($annotation) {
				return $annotation->getAnnotation() == '@var';
			})->transform(function ($annotation) {
				return $annotation->getType();
			})->first();
	}

	/**
	 * Checks if the property is public
	 *
	 * @return bool
	 */
	public function isPublic()
	{
		return $this->property->isPublic();
	}

	/**
	 * Set the value of the property
	 *
	 * @param $object
	 * @param $value
	 */
	public function setValue($object, $value)
	{
		$this->property->setValue($object, $value);
	}

	/**
	 * Set the accessibilty of the property
	 *
	 * @param $bool
	 */
	public function setAccessible($bool)
	{
		$this->property->setAccessible($bool);
	}

	/**
	 * Get the docblock of the property
	 *
	 * @return string
	 */
	protected function getDocblock()
	{
		return $this->property->getDocComment();
	}

}