<?php

namespace Gb\Mapper;

use Gb\Mapper\Tree\Node;

interface ParserInterface
{
	/**
	 * @param $xml
	 * @return Node
	 */
	public function parse($xml);
}