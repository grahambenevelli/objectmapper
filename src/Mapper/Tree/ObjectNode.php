<?php

namespace Gb\Mapper\Tree;

use stdClass;
use Tadgola\Collect\EloquentIterable;

/**
 * Class ObjectNode
 * @package Gb\Mapper\Tree
 */
class ObjectNode implements Node
{
	/**
	 * @var stdClass
	 */
	private $values;

	/**
	 * ObjectNode constructor.
	 * @param null $values
	 */
	public function __construct($values = null)
	{
		if (empty($values)) {
			$values = new stdClass();
		}
		$this->values = $values;
	}

	/**
	 * Get the type of the Node
	 *
	 * @return string
	 */
	public function getType()
	{
		return 'object';
	}

	/**
	 * Get the value of the Node
	 *
	 * @return object
	 */
	public function getValue()
	{
		return (object)$this->values;
	}

	/**
	 * Get the iterator over the values
	 *
	 * @return EloquentIterable
	 */
	public function getIterator()
	{
		return EloquentIterable::wrap((array)$this->values);
	}

	/**
	 * @param $property
	 * @return mixed
	 */
	public function __get($property)
	{
		return $this->values->$property;
	}

	/**
	 * @param $property
	 * @param $value
	 */
	public function __set($property, $value)
	{
		$this->values->$property = $value;
	}

	/**
	 * Returns if the Node is simple type
	 *
	 * @return bool
	 */
	public function isSimpleType()
	{
		return false;
	}
}