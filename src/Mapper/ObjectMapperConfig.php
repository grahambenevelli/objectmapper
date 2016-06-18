<?php

namespace Gb\Mapper;


class ObjectMapperConfig
{

	/**
	 * @var boolean
	 */
	private $convertEntries;

	/**
	 * ObjectMapperConfig constructor.
	 * @param $convertEntries
	 */
	public function __construct($convertEntries = false)
	{
		$this->convertEntries = $convertEntries;
	}

	/**
	 * @return boolean
	 */
	public function getConvertEntries()
	{
		return $this->convertEntries;
	}


}
