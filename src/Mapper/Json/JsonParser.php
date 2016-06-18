<?php

namespace Gb\Mapper\Json;

use Exception;
use Gb\Mapper\ParserInterface;
use Gb\Mapper\Tree\ArrayNode;
use Gb\Mapper\Tree\BasicTypeNode;
use Gb\Mapper\Tree\Node;
use Gb\Mapper\Tree\ObjectNode;
use stdClass;

/**
 * Class JsonParser
 * @package Gb\Mapper\Json
 */
class JsonParser implements ParserInterface
{

	/**
	 * Parse the given json string into a tree of Node objects
	 *
	 * @param string $json the json string
	 * @return Node
	 * @throws Exception
	 */
	public function parse($json)
	{
		if ($json instanceof Node) {
			return $json;
		}

		if (!is_string($json)) {
			if (is_array($json)) {
				return new ArrayNode($json);
			} else {
				return new BasicTypeNode($json);
			}
		}

		$parsed = json_decode($json);
		return $this->convertToNodes($parsed);
	}

	/**
	 * Convert the parsed object into a tree of Nodes
	 *
	 * @param mixed $parsed
	 * @return Node
	 * @throws Exception
	 */
	private function convertToNodes($parsed)
	{
		if (is_null($parsed)) {
			return null;
		}

		if ($this->isPrimitiveType($parsed)) {
			return new BasicTypeNode($parsed);
		}

		if (gettype($parsed) == 'array') {
			$arr = [];
			foreach ($parsed as $item) {
				$arr[] = $this->convertToNodes($item);
			}
			return new ArrayNode($arr);
		}

		if (gettype($parsed) == 'object') {
			$obj = new stdClass();
			foreach ($parsed as $key => $item) {
				$obj->$key = $this->convertToNodes($item);
			}
			return new ObjectNode($obj);
		}

		throw new Exception("Unknown type");
	}

	/**
	 * Checks to see if the type of the item is a primitive type
	 *
	 * @param $item
	 * @return bool
	 */
	private function isPrimitiveType($item)
	{
		$type = gettype($item);
		$primitiveTypes = [
			'string',
			'boolean',
			'bool',
			'integer',
			'int',
			'float',
			'double'
		];

		return in_array($type, $primitiveTypes);
	}
}