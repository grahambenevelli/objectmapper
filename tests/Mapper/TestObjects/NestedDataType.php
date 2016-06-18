<?php

namespace Gb\Mapper\TestObjects;


class NestedDataType
{
    /**
     * @var integer
     */
    public $integer;

    /**
     * @var \Gb\Mapper\TestObjects\PrimitiveDataTypes
     */
    public $nested;
}