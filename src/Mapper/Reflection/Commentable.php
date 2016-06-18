<?php

namespace Gb\Mapper\Reflection;


use Tadgola\Collect\EloquentIterable;

/**
 * Class Commentable
 * @package Gb\Mapper\Reflection
 */
abstract class Commentable
{

	/**
	 * Parse Annotation
	 *
	 * @return array
	 */
	protected function parseAnnotations()
	{
		$docblock = $this->getDocblock();
		return EloquentIterable::wrap(explode("\n", $docblock))
			->transform(function ($line) {
				$result = trim($line);
				$result = substr($result, 1);
				return trim($result);
			})->filter(function ($line) {
				$char = substr($line, 0, 1);
				return $char == '@';
			})->transform(function ($line) {
				return Annotation::fromLine($line);
			})->toArray();
	}

	/**
	 * Get the doc block for this object
	 *
	 * @return mixed
	 */
	protected abstract function getDocblock();
}