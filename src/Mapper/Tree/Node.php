<?php

namespace Gb\Mapper\Tree;

/**
 * Interface Node
 * @package Gb\Mapper\Tree
 */
interface Node
{
	/**
	 * Get the type of the Node
	 *
	 * @return mixed
	 */
	public function getType();

	/**
	 * Get the value of the Node
	 *
	 * @return mixed
	 */
	public function getValue();

	/**
	 * Is the value a simple type
	 *
	 * @return mixed
	 */
	public function isSimpleType();
}