<?php

namespace Gb\Mapper\Tree;

use stdClass;
use Tadgola\Collect\EloquentIterable;

/**
 * Class ObjectNode
 * @package Gb\Mapper\Tree
 */
class NullNode implements Node
{

	/**
	 * Get the type of the Node
	 *
	 * @return string
	 */
	public function getType()
	{
		return 'null';
	}

	/**
	 * Get the value of the Node
	 *
	 * @return object
	 */
	public function getValue()
	{
		return null;
	}

	/**
	 * Is the value a simple type
	 *
	 * @return mixed
	 */
	public function isSimpleType()
	{
		return true;
	}
}