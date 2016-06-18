<?php

namespace Gb\Mapper\Reflection\Annotation;

use Gb\Mapper\Reflection\Annotation;

/**
 * Basic Annotation, used for unknown type
 */
class BasicAnnotation extends Annotation
{

	/**
	 * @var string[]
	 */
	private $parts;

	/**
	 * VarAnnotation constructor.
	 *
	 * @param string[] $parts
	 */
	public function __construct($type, $parts)
	{
		parent::__construct($type);
		$this->parts = $parts;
	}
}