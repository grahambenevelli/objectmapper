<?php

namespace Gb\Mapper\Json;

use Gb\Mapper\Tree\ArrayNode;
use Gb\Mapper\Tree\BasicTypeNode;
use Gb\Mapper\Tree\ObjectNode;
use PHPUnit_Framework_TestCase;

/**
 * Class JsonObjectMapperTest
 * @package Gb\Mapper\Json
 */
class JsonParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var JsonParser
     */
    private $parser;

    public function setUp()
    {
        parent::setUp();
        $this->parser = new JsonParser();
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
    public function testParsing($json, $expected)
    {
        $result = $this->parser->parse($json);
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

        $expected = new ObjectNode();
        $expected->integer = new BasicTypeNode(3);
        $expected->string = new BasicTypeNode('test string');
        $expected->boolean = new BasicTypeNode(true);
        $expected->float = new BasicTypeNode(1.1);
        $expected->array = new ArrayNode([
            new BasicTypeNode(1),
            new BasicTypeNode(2),
            new BasicTypeNode(3),
        ]);
        $expected->secondArray = new ArrayNode([
            new BasicTypeNode(4)
        ]);

        return [
            $json,
            $expected,
        ];
    }

    /**
     * @return array
     */
    private function getNullableTest()
    {
        $json = "{
            \"integer\": null,
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

        $expected = new ObjectNode();
        $expected->integer = null;
        $expected->string = new BasicTypeNode('test string');
        $expected->boolean = new BasicTypeNode(true);
        $expected->float = new BasicTypeNode(1.1);
        $expected->array = new ArrayNode([
            new BasicTypeNode(1),
            new BasicTypeNode(2),
            new BasicTypeNode(3),
        ]);
        $expected->secondArray = new ArrayNode([
            new BasicTypeNode(4)
        ]);

        return [
            $json,
            $expected,
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
        $nested = new ObjectNode();
        $nested->integer = new BasicTypeNode(3);
        $nested->string = new BasicTypeNode('test string');
        $nested->boolean = new BasicTypeNode(true);
        $nested->float = new BasicTypeNode(1.1);
        $nested->array = new ArrayNode([
            new BasicTypeNode(1),
            new BasicTypeNode(2),
            new BasicTypeNode(3),
        ]);
        $nested->secondArray = new ArrayNode([
            new BasicTypeNode(4)
        ]);

        $expected = new ObjectNode();
        $expected->integer = new BasicTypeNode(99);
        $expected->nested = $nested;

        return [
            $json,
            $expected,
        ];
    }
}