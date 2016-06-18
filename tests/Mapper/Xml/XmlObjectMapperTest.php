<?php

namespace Gb\Mapper\Xml;

use Gb\Mapper\OtherNamespace\SimpleDataTypeInOtherNamespace;
use Gb\Mapper\TestObjects\MixedDataType;
use Gb\Mapper\TestObjects\NestedDataType;
use Gb\Mapper\TestObjects\NestedOtherNamespaceType;
use Gb\Mapper\TestObjects\PrimitiveDataTypes;
use Gb\Mapper\TestObjects\PrimitiveDataTypesWithPrivateProps;
use Gb\Mapper\TestObjects\PrimitiveDataTypesWithSetters;
use Gb\Mapper\TestObjects\PrivateArray;
use Gb\Mapper\TestObjects\SettersWithClass;
use Gb\Mapper\Xml\XmlObjectMapper;
use PHPUnit_Framework_TestCase;

/**
 * Class JsonObjectMapperTest
 * @package Gb\Mapper\Json
 */
class XmlObjectMapperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var XmlObjectMapper
     */
    private $mapper;

    public function setUp()
    {
        parent::setUp();
        $this->mapper = new XmlObjectMapper();
    }

    public function parsingDataProvider() {
        return [
            $this->getPrimitiveTypeTest(),
            $this->getPrimitiveTypeSingleArrayElementTest(),
            $this->getPrimitiveTypeSettersTest(),
            $this->getSettersWithClass(),
            $this->getPrimitiveTypeWithPrivatePropsTest(),
            $this->getPrivateArrayTest(),
            $this->getNestedTypesTest(),
            $this->getMixedTypeTest(),
            $this->getOtherNamespaceTest(),
        ];
    }

    /**
     * @dataProvider parsingDataProvider
     * @group now
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
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <PrimitiveDataTypes>
                <integer>3</integer>
                <string>test string</string>
                <boolean>true</boolean>
                <float>1.1</float>
                <array>1</array>
                <array>2</array>
                <array>3</array>
            </PrimitiveDataTypes>";
        $expected = new PrimitiveDataTypes();
        $expected->integer = 3;
        $expected->string = 'test string';
        $expected->boolean = true;
        $expected->float = 1.1;
        $expected->array = [1, 2, 3];

        return [
            $xml,
            $expected,
            new PrimitiveDataTypes(),
        ];
    }

    /**
     * @return array
     */
    private function getPrimitiveTypeSingleArrayElementTest()
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <PrimitiveDataTypes>
                <integer>3</integer>
                <string>test string</string>
                <boolean>true</boolean>
                <float>1.1</float>
                <array>1</array>
            </PrimitiveDataTypes>";
        $expected = new PrimitiveDataTypes();
        $expected->integer = 3;
        $expected->string = 'test string';
        $expected->boolean = true;
        $expected->float = 1.1;
        $expected->array = [1];

        return [
            $xml,
            $expected,
            new PrimitiveDataTypes(),
        ];
    }

    /**
     * @return array
     */
    private function getPrimitiveTypeSettersTest()
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <PrimitiveDataTypesWithSetters>
                <integer>3</integer>
                <string>test string</string>
                <boolean>true</boolean>
                <float>1.1</float>
                <array>1</array>
                <array>2</array>
                <array>3</array>
            </PrimitiveDataTypesWithSetters>";
        $expected = new PrimitiveDataTypesWithSetters();
        $expected->integer = 4;
        $expected->string = 'test string_bak';
        $expected->boolean = false;
        $expected->float = 2.2;
        $expected->array = [1, 2, 3, 0];

        return [
            $xml,
            $expected,
            new PrimitiveDataTypesWithSetters(),
        ];
    }

    /**
     * @return array
     */
    private function getSettersWithClass()
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <SettersWithClass>
                <object>
                    <integer>3</integer>
                    <string>test string</string>
                    <boolean>true</boolean>
                    <float>1.1</float>
                    <array>1</array>
                    <array>2</array>
                    <array>3</array>
                </object>
            </SettersWithClass>";

        $object = new PrimitiveDataTypes();
        $object->integer = 3;
        $object->string = 'test string';
        $object->boolean = true;
        $object->float = 1.1;
        $object->array = [1, 2, 3];

        $expected = new SettersWithClass();
        $expected->setObject($object);

        return [
            $xml,
            $expected,
            new SettersWithClass(),
        ];
    }

    /**
     * @return array
     */
    private function getPrimitiveTypeWithPrivatePropsTest()
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <PrimitiveDataTypesWithPrivateProps>
                <integer>3</integer>
                <string>test string</string>
                <boolean>true</boolean>
                <float>1.1</float>
                <array>1</array>
                <array>2</array>
                <array>3</array>
            </PrimitiveDataTypesWithPrivateProps>";

        $expected = PrimitiveDataTypesWithPrivateProps::getInstance(
            3,
            'test string',
            true,
            1.1,
            [1, 2, 3],
            null
        );

        return [
            $xml,
            $expected,
            new PrimitiveDataTypesWithPrivateProps(),
        ];
    }

    /**
     * @return array
     */
    private function getPrivateArrayTest()
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <PrivateArray>
                <array>1</array>
                <array>2</array>
                <array>3</array>
            </PrivateArray>";

        $expected = PrivateArray::getInstance(
            [1, 2, 3]
        );

        return [
            $xml,
            $expected,
            new PrivateArray(),
        ];
    }

    /**
     * @return array
     */
    private function getNestedTypesTest()
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <NestedDataType>
                <integer>99</integer>
                <nested>
                    <integer>3</integer>
                    <string>test string</string>
                    <boolean>true</boolean>
                    <float>1.1</float>
                    <array>1</array>
                    <array>2</array>
                    <array>3</array>
                </nested>
            </NestedDataType>";

        $nestedObject = new PrimitiveDataTypes();
        $nestedObject->integer = 3;
        $nestedObject->string = 'test string';
        $nestedObject->boolean = true;
        $nestedObject->float = 1.1;
        $nestedObject->array = [1, 2, 3];

        $expected = new NestedDataType();
        $expected->integer = 99;
        $expected->nested = $nestedObject;

        return [
            $xml,
            $expected,
            new NestedDataType(),
        ];
    }

    /**
     * @return array
     */
    private function getOtherNamespaceTest()
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <NestedOtherNamespaceType>
                <otherNamespace>
                    <integer>3</integer>
                </otherNamespace>
            </NestedOtherNamespaceType>";
        $nestedObject = new SimpleDataTypeInOtherNamespace();
        $nestedObject->integer = 3;

        $expected = new NestedOtherNamespaceType();
        $expected->otherNamespace = $nestedObject;

        return [
            $xml,
            $expected,
            new NestedOtherNamespaceType(),
        ];
    }

    /**
     * @return array
     */
    private function getMixedTypeTest()
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <MixedDataType>
                <mixed>99</mixed>
            </MixedDataType>";
        $expected = new MixedDataType();
        $expected->mixed = 99;

        return [
            $xml,
            $expected,
            new MixedDataType(),
        ];
    }
}