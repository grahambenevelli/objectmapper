<?php

namespace Gb\Mapper\Xml;

use Gb\Mapper\Tree\ArrayNode;
use Gb\Mapper\Tree\BasicTypeNode;
use Gb\Mapper\Tree\ObjectNode;
use PHPUnit_Framework_TestCase;

/**
 * Class JsonObjectMapperTest
 * @package Gb\Mapper\Json
 */
class XmlParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var XmlParser
     */
    private $parser;

    public function setUp()
    {
        parent::setUp();
        $this->parser = new XmlParser();
    }

    public function parsingDataProvider() {
        return [
            // Base tests
            $this->getPrimitiveTypeTest(),
            $this->getNestedTypesTest(),
            $this->getNullableTest(),
        ];
    }

    /**
     * @dataProvider parsingDataProvider
     */
    public function testParsing($xml, $expected)
    {
        $result = $this->parser->parse($xml);
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
                <secondArray>4</secondArray>
            </PrimitiveDataTypes>";

        $expected = new ObjectNode();
        $expected->integer = new BasicTypeNode(3);
        $expected->string = new BasicTypeNode('test string');
        $expected->boolean = new BasicTypeNode('true');
        $expected->float = new BasicTypeNode(1.1);
        $expected->array = new ArrayNode([
            new BasicTypeNode(1),
            new BasicTypeNode(2),
            new BasicTypeNode(3),
        ]);
        $expected->secondArray = new BasicTypeNode(4);

        return [
            $xml,
            $expected,
        ];
    }

    /**
     * @return array
     */
    private function getNullableTest()
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <PrimitiveDataTypes>
                <integer>null</integer>
                <string>test string</string>
                <boolean>true</boolean>
                <float>1.1</float>
                <array>1</array>
                <array>2</array>
                <array>3</array>
                <secondArray>4</secondArray>
            </PrimitiveDataTypes>";

        $expected = new ObjectNode();
        $expected->integer = new BasicTypeNode('null');
        $expected->string = new BasicTypeNode('test string');
        $expected->boolean = new BasicTypeNode('true');
        $expected->float = new BasicTypeNode(1.1);
        $expected->array = new ArrayNode([
            new BasicTypeNode(1),
            new BasicTypeNode(2),
            new BasicTypeNode(3),
        ]);
        $expected->secondArray = new BasicTypeNode(4);

        return [
            $xml,
            $expected,
        ];
    }

    /**
     * @return array
     */
    private function getNestedTypesTest()
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <object>
                <integer>99</integer>
                <nested>
                    <integer>3</integer>
                    <string>test string</string>
                    <boolean>true</boolean>
                    <float>1.1</float>
                    <array>1</array>
                    <array>2</array>
                    <array>3</array>
                    <secondArray>4</secondArray>
                </nested>
            </object>";

        $nested = new ObjectNode();
        $nested->integer = new BasicTypeNode(3);
        $nested->string = new BasicTypeNode('test string');
        $nested->boolean = new BasicTypeNode('true');
        $nested->float = new BasicTypeNode(1.1);
        $nested->array = new ArrayNode([
            new BasicTypeNode(1),
            new BasicTypeNode(2),
            new BasicTypeNode(3),
        ]);
        $nested->secondArray = new BasicTypeNode(4);

        $expected = new ObjectNode();
        $expected->integer = new BasicTypeNode(99);
        $expected->nested = $nested;

        return [
            $xml,
            $expected,
        ];
    }
}