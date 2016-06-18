<?php

namespace Gb\Mapper\Json;

use Gb\Mapper\OtherNamespace\SimpleDataTypeInOtherNamespace;
use Gb\Mapper\TestObjects\MixedDataType;
use Gb\Mapper\TestObjects\NestedDataType;
use Gb\Mapper\TestObjects\NestedOtherNamespaceType;
use Gb\Mapper\TestObjects\PrimitiveDataTypes;
use Gb\Mapper\TestObjects\PrimitiveDataTypesWithPrivateProps;
use Gb\Mapper\TestObjects\PrimitiveDataTypesWithSetters;
use Gb\Mapper\TestObjects\PrivateArray;
use Gb\Mapper\TestObjects\SettersWithClass;
use PHPUnit_Framework_TestCase;

/**
 * Class JsonObjectMapperTest
 * @package Gb\Mapper\Json
 */
class JsonObjectMapperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var JsonObjectMapper
     */
    private $mapper;

    public function setUp()
    {
        parent::setUp();
        $this->mapper = new JsonObjectMapper();
    }

    public function parsingDataProvider() {
        return [
            $this->getIntegerTest(),
            $this->getStringTest(),
            $this->getBooleanTest(),
            $this->getFloatTest(),
            $this->getArrayTest(),
            $this->getPrimitiveTypeTest(),
            $this->getPrimitiveTypeSettersTest(),
            $this->getPrimitiveTypeWithPrivatePropsTest(),
            $this->getPrivateArrayTest(),
            $this->getNestedTypesTest(),
            $this->getMixedTypeTest(),
            $this->getOtherNamespaceTest(),
            $this->getSettersWithClass(),
        ];
    }

    /**
     * @dataProvider parsingDataProvider
     */
    public function testParsing($json, $expected, $object)
    {
        $result = $this->mapper->map($json, $object);
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    private function getPrimitiveTypeTest()
    {
        $json = "{
            \"integer\": 3,
            \"string\": \"test string\",
            \"boolean\": true,
            \"float\": 1.1,
            \"array\": [
                1,
                2,
                3
            ],
            \"secondArray\": [
                4
            ]
        }";
        $expected = new PrimitiveDataTypes();
        $expected->integer = 3;
        $expected->string = 'test string';
        $expected->boolean = true;
        $expected->float = 1.1;
        $expected->array = [1, 2, 3];
        $expected->secondArray = [4];

        return [
            $json,
            $expected,
            new PrimitiveDataTypes(),
        ];
    }

    /**
     * @return array
     */
    private function getSettersWithClass()
    {
        $json = "{
            \"object\": {
                \"integer\": 3,
                \"string\": \"test string\",
                \"boolean\": true,
                \"float\": 1.1,
                \"array\": [
                    1,
                    2,
                    3
                ],
                \"secondArray\": [
                    4
                ]
            }
        }";

        $object = new PrimitiveDataTypes();
        $object->integer = 3;
        $object->string = 'test string';
        $object->boolean = true;
        $object->float = 1.1;
        $object->array = [1, 2, 3];
        $object->secondArray = [4];

        $expected = new SettersWithClass();
        $expected->setObject($object);

        return [
            $json,
            $expected,
            new SettersWithClass(),
        ];
    }

    /**
     * @return array
     */
    private function getPrimitiveTypeWithPrivatePropsTest()
    {
        $json = "{
            \"integer\": 3,
            \"string\": \"test string\",
            \"boolean\": true,
            \"float\": 1.1,
            \"array\": [
                1,
                2,
                3
            ],
            \"secondArray\": [
                4
            ]
        }";
        $expected = PrimitiveDataTypesWithPrivateProps::getInstance(
            3,
            'test string',
            true,
            1.1,
            [1, 2, 3],
            [4]
        );

        return [
            $json,
            $expected,
            new PrimitiveDataTypesWithPrivateProps(),
        ];
    }

    /**
     * @return array
     */
    private function getPrivateArrayTest()
    {
        $json = "{
            \"array\": [
                1,
                2,
                3
            ]
        }";
        $expected = PrivateArray::getInstance(
            [1, 2, 3]
        );

        return [
            $json,
            $expected,
            new PrivateArray(),
        ];
    }

    /**
     * @return array
     */
    private function getPrimitiveTypeSettersTest()
    {
        $json = "{
            \"integer\": 3,
            \"string\": \"test string\",
            \"boolean\": true,
            \"float\": 1.1,
            \"array\": [
                1,
                2,
                3
            ],
            \"secondArray\": [
                4
            ]
        }";
        $expected = new PrimitiveDataTypesWithSetters();
        $expected->integer = 4;
        $expected->string = 'test string_bak';
        $expected->boolean = false;
        $expected->float = 2.2;
        $expected->array = [1, 2, 3, 0];
        $expected->secondArray = [4, 0];

        return [
            $json,
            $expected,
            new PrimitiveDataTypesWithSetters(),
        ];
    }

    /**
     * @return array
     */
    private function getNestedTypesTest()
    {
        $json = "{
            \"integer\": 99,
            \"nested\": {
                \"integer\": 3,
                \"string\": \"test string\",
                \"boolean\": true,
                \"float\": 1.1,
                \"array\": [
                    1,
                    2,
                    3
                ],
                \"secondArray\": [
                    4
                ]
            }
        }";
        $nestedObject = new PrimitiveDataTypes();
        $nestedObject->integer = 3;
        $nestedObject->string = 'test string';
        $nestedObject->boolean = true;
        $nestedObject->float = 1.1;
        $nestedObject->array = [1, 2, 3];
        $nestedObject->secondArray = [4];

        $expected = new NestedDataType();
        $expected->integer = 99;
        $expected->nested = $nestedObject;

        return [
            $json,
            $expected,
            new NestedDataType(),
        ];
    }

    /**
     * @return array
     */
    private function getOtherNamespaceTest()
    {
        $json = "{
            \"otherNamespace\": {
                \"integer\": 3
            }
        }";
        $nestedObject = new SimpleDataTypeInOtherNamespace();
        $nestedObject->integer = 3;

        $expected = new NestedOtherNamespaceType();
        $expected->otherNamespace = $nestedObject;

        return [
            $json,
            $expected,
            new NestedOtherNamespaceType(),
        ];
    }

    /**
     * @return array
     */
    private function getMixedTypeTest()
    {
        $json = "{
            \"mixed\": 99
        }";
        $expected = new MixedDataType();
        $expected->mixed = 99;

        return [
            $json,
            $expected,
            new MixedDataType(),
        ];
    }

    private function getIntegerTest()
    {
        $json = '1';
        $expected = 1;

        return [
            $json,
            $expected,
            'integer',
        ];
    }

    private function getStringTest()
    {
        $json = '"test string"';
        $expected = 'test string';

        return [
            $json,
            $expected,
            'string',
        ];
    }

    private function getBooleanTest()
    {
        $json = 'true';
        $expected = true;

        return [
            $json,
            $expected,
            'boolean',
        ];
    }

    private function getFloatTest()
    {
        $json = '1.1';
        $expected = 1.1;

        return [
            $json,
            $expected,
            'float',
        ];
    }

    private function getArrayTest()
    {
        $json = '[1, 2, 3]';
        $expected = [1, 2, 3];

        return [
            $json,
            $expected,
            'array',
        ];
    }
}