<?php

namespace Gb\Mapper;


interface IObjectMapper
{

	/**
	 * Read the value of the given string
	 *
	 * @param $str
	 * @param $object
	 * @return mixed
	 */
	public function map($str, $object);

}