<?php

namespace Gb\Mapper\Reflection;

use ReflectionMethod;
use ReflectionParameter;
use Tadgola\Collect\EloquentIterable;
use Tadgola\Optional\Optional;

/**
 * Class Method
 * @package Gb\Mapper\Reflection
 */
class Setter extends Commentable
{
	/**
	 * @var ReflectionMethod
	 */
	private $method;

	/**
	 * @var mixed
	 */
	private $object;

	/**
	 * Method constructor.
	 * @param ReflectionMethod $method
	 */
	private function __construct($object, ReflectionMethod $method)
	{
		$this->method = $method;
		$this->object = $object;
	}

	/**
	 * Get the Method object of the given ReflectionMethod
	 *
	 * @param ReflectionMethod $method
	 * @return Setter
	 */
	public static function of($object, ReflectionMethod $method)
	{
		return new Setter($object, $method);
	}

	/**
	 * Returns if the method is public or not
	 *
	 * @return bool
	 */
	public function isPublic()
	{
		return $this->method->isPublic();
	}

	/**
	 * Invoke the method with on the given object with the given value
	 *
	 * @param $value
	 */
	public function invoke($value)
	{
		$args = func_get_args();
		array_unshift($args, $this->object);
		call_user_func_array(array($this->method, "invoke"), $args);
	}

	/**
	 * Get the type of the first parameter of the method
	 *
	 * @return Optional
	 */
	public function getType()
	{
		$hinted = $this->getSetterHintedType();
		if ($hinted->isPresent()) {
			return Optional::of($hinted->get());
		}

		$annotations = $this->parseAnnotations();
		return EloquentIterable::wrap($annotations)
			->filter(function ($annotation) {
				return $annotation->getAnnotation() == '@param';
			})->transform(function ($annotation) {
				return $annotation->getType();
			})->first();
	}

	/**
	 * Get the hinted type of the first parameter
	 *
	 * @return Optional
	 */
	private function getSetterHintedType()
	{
		$optionalParam = EloquentIterable::wrap($this->method->getParameters())
			->first();

		if ($optionalParam->isPresent()) {
			/**
			 * @var $param ReflectionParameter
			 */
			$param = $optionalParam->get();
			$class = $param->getClass();
			if ($class !== null) {
				return Optional::of($class->getName());
			}
		}
		return Optional::absent();
	}

	/**
	 * Get the docblock
	 *
	 * @return string
	 */
	protected function getDocblock()
	{
		return $this->method->getDocComment();
	}
}