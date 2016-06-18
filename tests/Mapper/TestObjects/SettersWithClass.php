<?php

namespace Gb\Mapper\TestObjects;


class SettersWithClass
{
    /**
     * @var integer
     */
    public $object;

    public function setObject(PrimitiveDataTypes $object)
    {
        $this->object = $object;
    }
}