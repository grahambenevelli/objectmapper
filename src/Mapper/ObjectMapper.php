<?php

namespace Gb\Mapper;

use Exception;
use Gb\Mapper\Reflection\PropertyAccessor;
use Gb\Mapper\Tree\Node;
use Gb\Mapper\Tree\ObjectNode;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Class ObjectMapper
 * @package Gb\Mapper
 *
 * TODO make this use property definable by annotation
 * TODO fucking namespaces
 */
abstract class ObjectMapper implements IObjectMapper
{

	/**
	 * @var ParserInterface
	 */
	protected $parser;

	/**
	 * @var ObjectMapperConfig
	 */
	protected $config;

	/**
	 * ObjectMapper constructor.
	 * @param ParserInterface $parser
	 * @param ObjectMapperConfig $config
	 */
	public function __construct(ParserInterface $parser, ObjectMapperConfig $config)
	{
		$this->parser = $parser;
		$this->config = $config;
	}

	/**
	 * Read the the string and map into the object
	 *
	 * @param $str
	 * @param $object
	 * @param null $subtype
	 * @return mixed
	 * @throws Exception
	 */
	public function map($str, $object, $subtype = null)
	{
		$parsed = $this->parser->parse($str);
		return $this->processParsed($parsed, $object, $subtype);
	}

	/**
	 *
	 * @param Node $parsed
	 * @param $type
	 * @param $subtype
	 * @return array
	 * @throws Exception
	 */
	private function processParsed(Node $parsed, $type, $subtype = null)
	{
		if ($parsed->isSimpleType()) {
			if ($this->config->getConvertEntries() && $parsed->getType() != $type) {
				return $this->convertNode($parsed, $type, $subtype);
			}
			return $parsed->getValue();
		}

		if ($parsed->getType() == 'array') {
			$result = [];
			foreach ($parsed->getValue() as $key => $item) {
				$result[] = $this->processParsed($item, $subtype);
			}
			return $result;
		}

		if ($parsed->getType() == 'object') {
			if ($this->config->getConvertEntries() &&
				is_string($type) &&
				substr($type, -2) == '[]') {
				return [
					$this->processParsedObject($parsed, $this->getSubtype($type))
				];
			}
			return $this->processParsedObject($parsed, $type);
		}

		throw new MapperException('Unknown type');
	}

	private function convertNode(Node $parsed, $type, $subtype = null)
	{
		$value = $parsed->getValue();

		if ($type == 'mixed') {
			return $value;
		}
		if ($type == 'array') {
			return [$value];
		}

		if (substr($type, -2) == '[]') {
			settype($value, $subtype);
			return [$value];
		}

		settype($value, $type);
		return $value;
	}

	/**
	 * Process a parsed object node
	 *
	 * @param ObjectNode $parsed
	 * @param $object
	 * @return mixed
	 * @throws MapperException
	 */
	private function processParsedObject(ObjectNode $parsed, $object)
	{
		$object = $this->instantiateObject($object);
		$rc = new ReflectionClass($object);
		$propertyRelationship = $this->getPropertyRelationships($object, $rc);

		foreach ($parsed->getIterator() as $key => $value) {
			/**
			 * @var $property PropertyAccessor
			 */
			$property = $propertyRelationship[$key];
			if (empty($property)) {
				throw new MapperException('Property doesn\'t exist on the Object');
			}

			$result = $this->processParsed(
				$value,
				$property->getPropertyType(),
				$this->getSubtype($property->getPropertyType())
			);
			$property->setProperty($object, $result);
		}

		return $object;
	}

	/**
	 * Create the object given a string
	 *
	 * @param mixed $object
	 * @return mixed
	 * @throws MapperException
	 */
	private function instantiateObject($object)
	{
		if (is_object($object)) {
			// already instance of an object
			return $object;
		}

		if (is_string($object)) {
			// name of class
			return new $object();
		}

		$type = gettype($object);
		throw new MapperException("object was not of correct type, was $type");
	}

	/**
	 * Get a map of property accessors
	 *
	 * @param $object
	 * @param ReflectionClass $rc
	 * @return array
	 */
	private function getPropertyRelationships($object, ReflectionClass $rc)
	{
		/**
		 * @var $properties ReflectionProperty[]
		 */
		$properties = $rc->getProperties();
		$result = [];

		/**
		 * @var $methods ReflectionMethod[]
		 */
		$methods = $rc->getMethods();
		$setters = [];
		foreach ($methods as $method) {
			if ($method->isPublic() && strpos($method->getName(), 'set') === 0) {
				$key = lcfirst(substr($method->getName(), 3));
				$setters[$key] = $method;
			}
		}

		/**
		 * @var $property ReflectionProperty
		 */
		foreach ($properties as $property) {
			$key = $property->getName();
			$result[$key] = new PropertyAccessor(
				$object,
				$property,
				isset($setters[$key]) ? $setters[$key] : null
			);
		}

		return $result;
	}

	/**
	 * Get the subtype of the parameter type
	 *
	 * @param $type
	 * @return null|string
	 */
	private function getSubtype($type)
	{
		$subtype = null;
		if (substr($type, -2) == '[]') {
			$subtype = substr($type, 0, -2);
			return $subtype;
		} else if (substr($type, -1) == ']') {
			$subtype = explode('[', substr($type, 0, -1))[1];
			return $subtype;
		}
		return $subtype;
	}
}
