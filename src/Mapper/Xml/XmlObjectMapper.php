<?php

namespace Gb\Mapper\Xml;

use Gb\Mapper\ObjectMapper;
use Gb\Mapper\ObjectMapperConfig;

class XmlObjectMapper extends ObjectMapper
{

	public function __construct(XmlParser $xmlParser = null)
	{
		if (is_null($xmlParser)) {
			$xmlParser = new XmlParser();
		}
		parent::__construct($xmlParser, new ObjectMapperConfig(true));
	}

}