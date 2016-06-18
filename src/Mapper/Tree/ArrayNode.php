<?php

namespace Gb\Mapper\Tree;

use Tadgola\Collect\EloquentIterable;

/**
 * Class ArrayNode
 * @package Gb\Mapper\Tree
 */
class ArrayNode implements Node
{
	private $values;

	public function __construct($values)
	{
		$this->values = $values;
	}

	/**
	 * Get the type of the Node
	 *
	 * @return mixed
	 */
	public function getType()
	{
		return 'array';
	}

	/**
	 * Get the value of the Node
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->values;
	}

	/**
	 * Get the iterator going over the given values
	 *
	 * @return EloquentIterable
	 */
	public function getIterator()
	{
		return EloquentIterable::wrap($this->values);
	}

	/**
	 * Is the value a simple type
	 *
	 * @return mixed
	 */
	public function isSimpleType()
	{
		return false;
	}
}