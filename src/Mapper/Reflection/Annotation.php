<?php

namespace Gb\Mapper\Reflection;

use Gb\Mapper\Reflection\Annotation\BasicAnnotation;
use Gb\Mapper\Reflection\Annotation\ParamAnnotation;
use Gb\Mapper\Reflection\Annotation\VarAnnotation;

/**
 * Class Annotation
 * @package Gb\Mapper\Reflection
 */
abstract class Annotation
{
	const PARAM = '@param';
	const VARIABLE = '@var';

	/**
	 * @var string
	 */
	private $annotation;

	/**
	 * Annotation constructor.
	 *
	 * @param string $annotation
	 */
	public function __construct($annotation)
	{
		$this->annotation = $annotation;
	}

	/**
	 * Instantiate Annotation object from line
	 *
	 * @param string $line
	 * @return Annotation
	 */
	public static function fromLine($line)
	{
		$parts = explode(' ', $line);
		$type = $parts[0];
		array_shift($parts);
		switch ($type) {
			case Annotation::VARIABLE:
				return new VarAnnotation($parts);
			case Annotation::PARAM:
				return new ParamAnnotation($parts);
			default:
				return new BasicAnnotation($type, $parts);
		}
	}

	/**
	 * Get the name of the annotation
	 *
	 * @return string
	 */
	public function getAnnotation()
	{
		return $this->annotation;
	}
}