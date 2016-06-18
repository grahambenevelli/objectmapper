<?php

namespace Gb\Mapper\TestObjects;


class PrimitiveDataTypesWithSetters
{
    /**
     * @var integer
     */
    public $integer;

    /**
     * @var string
     */
    public $string;

    /**
     * @var boolean
     */
    public $boolean;

    /**
     * @var float
     */
    public $float;

    /**
     * @var integer[]
     */
    public $array;

    /**
     * @var integer[]
     */
    public $secondArray;

    /**
     * @param int $integer
     */
    public function setInteger($integer)
    {
        $this->integer = $integer + 1;
    }

    /**
     * @param string $string
     */
    public function setString($string)
    {
        $this->string = $string . "_bak";
    }

    /**
     * @param boolean $boolean
     */
    public function setBoolean($boolean)
    {
        $this->boolean = !$boolean;
    }

    /**
     * @param float $float
     */
    public function setFloat($float)
    {
        $this->float = $float + 1.1;
    }

    /**
     * @param integer[] $array
     */
    public function setArray($array)
    {
        $array[] = 0;
        $this->array = $array;
    }

    /**
     * @param integer[] $secondArray
     */
    public function setSecondArray($secondArray)
    {
        $secondArray[] = 0;
        $this->secondArray = $secondArray;
    }
}