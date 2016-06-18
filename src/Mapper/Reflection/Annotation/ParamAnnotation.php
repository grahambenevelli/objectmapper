<?php

namespace Gb\Mapper\Reflection\Annotation;

use Gb\Mapper\Reflection\Annotation;

class ParamAnnotation extends Annotation
{
	/**
	 * @var string
	 */
	private $type;

	/**
	 * VarAnnotation constructor.
	 *
	 * @param string[] $parts
	 */
	public function __construct($parts)
	{
		parent::__construct(Annotation::PARAM);
		$this->type = isset($parts[0]) ? $parts[0] : null;
	}

	/**
	 * Get the type of the annotation
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}
}