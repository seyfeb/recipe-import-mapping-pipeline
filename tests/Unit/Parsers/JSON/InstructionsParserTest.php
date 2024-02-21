<?php
namespace RecipeImportPipeline\Unit\Parsers\JSON;

use PHPUnit\Framework\TestCase;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\HowToDirection;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\Instructions;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainTextInstruction;
use RecipeImportPipeline\Parsers\JSON\InstructionsParser;

class InstructionsParserTest extends TestCase
{

    public function testParsingSingleInstructionHowToDirection()
    {
        // Arrange
        $parser = new InstructionsParser();
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
        $instructions = $parser->getInstructions();

        // Assert
        $this->assertInstanceOf(Instructions::class, $instructions);
        $this->assertCount(1, $instructions);

    }

    public function testParsingInstructionArrayWithHowToDirections()
    {
        // Arrange
        $parser = new InstructionsParser();
        // Create a mock JSON object representing a single instruction
        $singleInstructionJson = new JSONObject([
            '@type' => new JSONString('HowToDirection'),
            'Text' => new JSONString('Chop the vegetables'),
            'Supply' => new JSONArray([
                new JSONString('Vegetables'),
                new JSONString('Oil'),
            ]),
        ]);

        // Create a mock JSON object representing an array of instructions
        $instructionArrayJson = new JSONArray([
            $singleInstructionJson,
            $singleInstructionJson,
        ]);

        // Act
        $instructionArrayJson->parseWith($parser);
        $instructionArray = $parser->getInstructions();

        // Assert
        $this->assertInstanceOf(Instructions::class, $instructionArray);
         $this->assertCount(2, $instructionArray);
    }

    public function testParsingInstructionArrayWithHowToDirectionAndString()
    {
        // Arrange
        $parser = new InstructionsParser();
        $singleInstructionJson = new JSONObject([
            '@type' => new JSONString('HowToDirection'),
            'Text' => new JSONString('Chop the vegetables'),
            'Supply' => new JSONArray([
                new JSONString('Vegetables'),
                new JSONString('Oil'),
            ]),
        ]);

        // Create a mock JSON object representing an array of instructions
        $instructionArrayJson = new JSONArray([
            $singleInstructionJson,
            new JSONString('Do another thing.')
        ]);

        // Act
        $instructionArrayJson->parseWith($parser);
        $instructionArray = $parser->getInstructions();

        // Assert
        $this->assertInstanceOf(Instructions::class, $instructionArray);
        $this->assertCount(2, $instructionArray);
        $this->assertInstanceOf(HowToDirection::class, $instructionArray[0]);
        $this->assertInstanceOf(PlainTextInstruction::class, $instructionArray[1]);
    }

    public function testParsingInstructionArrayWithStrings()
    {
        // Arrange
        $parser = new InstructionsParser();
        // Create a mock JSON object representing an array of instructions
        $instructionArrayJson = new JSONArray([
            new JSONString('Do one thing.'),
            new JSONString('Do another thing.')
        ]);

        // Act
        $instructionArrayJson->parseWith($parser);
        $instructionArray = $parser->getInstructions();

        // Assert
        $this->assertInstanceOf(Instructions::class, $instructionArray);
        $this->assertCount(2, $instructionArray);
        $this->assertInstanceOf(PlainTextInstruction::class, $instructionArray[0]);
        $this->assertInstanceOf(PlainTextInstruction::class, $instructionArray[1]);
    }
}