<?php

namespace Gb\Mapper\Tree;

use Exception;

/**
 * Class BasicTypeNode
 * @package Gb\Mapper\Tree
 */
class BasicTypeNode implements Node
{
	/**
	 * @var mixed
	 */
	private $value;

	/**
	 * BasicTypeNode constructor.
	 * @param $value
	 */
	public function __construct($value)
	{
		$this->value = $value;
	}

	/**
	 * Get the type of the of the Node's value
	 *
	 * @return string
	 */
	public function getType()
	{
		return gettype($this->value);
	}

	/**
	 * Get the value in the Node
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * Iterator on a Basic type doesn't work
	 *
	 * @throws Exception
	 */
	public function getIterator()
	{
		throw new Exception('BasicValueNode doesn\'t support iteration');
	}

	/**
	 * Is the Node's value a simple type
	 *
	 * @return bool
	 */
	public function isSimpleType()
	{
		return true;
	}
}