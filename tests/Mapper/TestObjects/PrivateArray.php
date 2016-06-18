<?php

namespace Gb\Mapper\TestObjects;


class PrivateArray
{

    /**
     * @var integer[]
     */
    private $array;

    public static function getInstance($array)
    {
        $ret = new self();
        $ret->array = $array;
        return $ret;
    }
}