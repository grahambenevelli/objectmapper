<?php

namespace Gb\Mapper\TestObjects;


class PrimitiveDataTypesWithPrivateProps
{
    /**
     * @var integer
     */
    private $integer;

    /**
     * @var string
     */
    private $string;

    /**
     * @var boolean
     */
    private $boolean;

    /**
     * @var float
     */
    private $float;

    /**
     * @var integer[]
     */
    private $array;

    /**
     * @var integer[]
     */
    private $secondArray;

    public static function getInstance($integer,
                                       $string,
                                       $boolean,
                                       $float,
                                       $array,
                                       $secondArray)
    {
        $ret = new self();
        $ret->integer = $integer;
        $ret->string = $string;
        $ret->boolean = $boolean;
        $ret->float = $float;
        $ret->array = $array;
        $ret->secondArray = $secondArray;
        return $ret;
    }
}