<?php

namespace Gb\Mapper\Json;

use Gb\Mapper\ObjectMapper;
use Gb\Mapper\ObjectMapperConfig;

/**
 * Class JsonObjectMappera
 * @package Gb\Mapper\Json
 */
class JsonObjectMapper extends ObjectMapper
{

	/**
	 * JsonObjectMapper constructor.
	 */
	public function __construct()
	{
		parent::__construct(
			new JsonParser(),
			new ObjectMapperConfig()
		);
	}
}