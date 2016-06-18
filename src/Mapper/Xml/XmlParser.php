<?php

namespace Gb\Mapper\Xml;

use Gb\Mapper\ParserInterface;
use Gb\Mapper\Tree\ArrayNode;
use Gb\Mapper\Tree\BasicTypeNode;
use Gb\Mapper\Tree\Node;
use Gb\Mapper\Tree\ObjectNode;
use Sabre\Xml\Reader;
use stdClass;
use Exception;

/**
 * Class XmlParser
 * @package Gb\Mapper\Xml
 */
class XmlParser implements ParserInterface
{
	/**
	 * @var Reader
	 */
	private $reader;

	/**
	 * XmlParser constructor.
	 */
	function __construct()
	{
		$this->reader = new Reader();
	}

	/**
	 * Parse an xml string
	 *
	 * @param $xml
	 * @return Node
	 * @throws Exception
	 * @throws \Sabre\Xml\LibXMLException
	 */
	public function parse($xml)
	{
		$this->reader->xml($xml);
		$parsed = $this->simplifyXml($this->reader->parse());
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
	 * Simplify the xml into a json decode kind of output
	 *
	 * @param $parsed
	 * @return stdClass
	 */
	private function simplifyXml($parsed)
	{
		return $this->simplifyXmlHelper($parsed['value']);
	}

	/**
	 * Simplify the xml into a json decode kind of output recursive
	 *
	 * @param $parsed
	 * @return stdClass
	 */
	private function simplifyXmlHelper($parsed)
	{
		if (!is_array($parsed)) {
			return $parsed;
		}

		$result = new stdClass();
		foreach ($parsed as $item) {
			$name = $this->parseName($item['name']);
			$value = $this->simplifyXmlHelper($item['value']);
			if (isset($result->$name)) {
				// handle the array case
				$arr = is_array($result->$name) ? $result->$name : [$result->$name];
				$arr[] = $value;
				$result->$name = $arr;
			} else {
				$result->$name = $value;
			}
		}
		return $result;
	}

	/**
	 * Parse the name from the xml
	 *
	 * @param $name
	 * @return string
	 */
	private function parseName($name)
	{
		$matches = [];
		preg_match('^({.*})?(.*)^', $name, $matches);
		return $this->snakeToCamel($matches[2]);
	}

	/**
	 * Convert snake to camel case
	 *
	 * @param $val
	 * @return string
	 */
	private function snakeToCamel($val) {
		return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $val))));
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