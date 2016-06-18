<?php

namespace Gb\Mapper\Reflection;

use Tadgola\Optional\Optional;

/**
 * Class PropertyAccessor
 * @package Gb\Mapper\Reflection
 */
class PropertyAccessor
{
	/**
	 * @var Property
	 */
	private $property;

	/**
	 * @var Optional
	 */
	private $setter;

	/**
	 * @var mixed
	 */
	private $object;

	/**
	 * PropertyAccessor constructor.
	 *
	 * @param $property
	 * @param $setter
	 */
	public function __construct($object, $property, $setter)
	{
		$this->object = $object;
		$this->property = Property::of($property);
		if ($setter) {
			$this->setter = Optional::of(Setter::of($object, $setter));
		} else {
			$this->setter = Optional::absent();
		}
	}

	/**
	 * Get the type of underlying property
	 *
	 * @return mixed|null
	 */
	public function getPropertyType()
	{
		if ($this->setter->isPresent() && $this->setter->get()->isPublic()) {
			return $this->setter->get()->getType()->getOrNull();
		}

		//now try to set the property directly
		return $this->property->getType()->getOrNull();
	}

	/**
	 * Get the property
	 *
	 * @return mixed
	 */
	public function getProperty()
	{
		return $this->property;
	}

	/**
	 * Set the value through the setter if needed, or directly on property on it
	 *
	 * @param $object
	 * @param $value
	 */
	public function setProperty($object, $value)
	{
		if ($this->setter->isPresent()) {
			$this->setter->get()->invoke($value);
		} else {
			$this->setOnProperty($object, $value);
		}
	}

	/**
	 * Set the value on the property
	 *
	 * @param $object
	 * @param $value
	 */
	private function setOnProperty($object, $value)
	{
		$reflectionProperty = $this->property;

		if ($reflectionProperty->isPublic()) {
			$reflectionProperty->setValue($object, $value);
		} else {
			// private method
			$reflectionProperty->setAccessible(true);
			$reflectionProperty->setValue($object, $value);
			$reflectionProperty->setAccessible(false);
		}

	}

	/**
	 * Get the setter
	 *
	 * @return mixed
	 */
	public function getSetter()
	{
		return $this->setter;
	}
}