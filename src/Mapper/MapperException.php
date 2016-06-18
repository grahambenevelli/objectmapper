<?php


namespace Gb\Mapper;

use Exception;

class MapperException extends Exception
{

	/**
	 * MapperException constructor.
	 * @param string $message
	 */
	public function __construct($message = "") {
		parent::__construct($message);
	}
}