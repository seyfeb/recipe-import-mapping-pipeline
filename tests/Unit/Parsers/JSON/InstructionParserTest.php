<?php
namespace RecipeImportPipeline\Tests\Unit\Parsers\JSON;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\HowToDirection;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainTextInstruction;
use RecipeImportPipeline\Interfaces\Entities\IInstruction;
use RecipeImportPipeline\Parsers\JSON\InstructionParser;

class InstructionParserTest extends TestCase
{
    public function testParsingSingleInstructionHowToDirection()
    {
        // Arrange
        $parser = new InstructionParser();
        // Create a mock JSON object representing a single instruction
        $singleInstructionJson = new JSONObject([
            '@type' => new JSONString('HowToDirection'),
            'Text' => new JSONString('Chop the vegetables'),
            'Supply' => new JSONArray([
                new JSONString('Vegetables'),
                new JSONString('Oil'),
            ]),
        ]);

        // Act
        $singleInstructionJson->parseWith($parser);
        $singleInstruction = $parser->getInstruction();

        // Assert
        $this->assertInstanceOf(HowToDirection::class, $singleInstruction);
        $this->assertInstanceOf(IInstruction::class, $singleInstruction);
    }
    public function testParsingSingleInstructionOfString()
    {
        // Arrange
        $parser = new InstructionParser();
        // Create a mock JSON object representing a single instruction
        $singleInstructionJson = new JSONString('Chop the vegetables');

        // Act
        $singleInstructionJson->parseWith($parser);
        $singleInstruction = $parser->getInstruction();

        // Assert
        $this->assertInstanceOf(PlainTextInstruction::class, $singleInstruction);
        $this->assertEquals('Chop the vegetables', $singleInstruction->getValue());
    }
}