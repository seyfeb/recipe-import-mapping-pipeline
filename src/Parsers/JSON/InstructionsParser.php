<?php

namespace RecipeImportPipeline\Parsers\JSON;

use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use RecipeImportPipeline\Entities\GenericJSON\JSONFloat;
use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\Instructions;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainTextInstruction;
use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;

class InstructionsParser implements IJSONObjectParser
{
    /**
     * @var ?Instructions Local value of parsed instructions.
     */
    private ?Instructions $instructions = null;

    /**
     * Accesses the value of the parsed instructions.
     * @return Instructions|null The instructions after parser has run.
     */
    public function getInstructions() : ?Instructions
    {
        return $this->instructions;
    }

    /**
     * @inheritDoc
     */
    public function handleInt(JSONInteger $value): void
    {
        // Not applicable for Instructions parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleBool(JSONBool $value): void
    {
        // Not applicable for Instructions parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleFloat(JSONFloat $value): void
    {
        // Not applicable for Instructions parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleString(JSONString $value): void
    {
        // Consider string as single instruction
        $this->instructions = new Instructions([new PlainTextInstruction($value->getValue())]);
    }

    /**
     * @inheritDoc
     */
    public function handleArray(JSONArray $value): void
    {
        $instructionParser = new InstructionParser();
        $arr = [];

        foreach ($value->getValue() as $item){
            $instructionParser->reset();
            $item->parseWith($instructionParser);
            $parsedInstruction = $instructionParser->getInstruction();
            if($parsedInstruction !== null) {
                $arr[] = $parsedInstruction;
            }
        }
        $this->instructions = new Instructions($arr);
    }

    /**
     * @inheritDoc
     */
    public function handleObject(JSONObject $value): void
    {
        // Try treating as single Instruction
        $instructionParser = new InstructionParser();
        $value->parseWith($instructionParser);
        $this->instructions = new Instructions([$instructionParser->getInstruction()]);
    }

    /**
     * Resets the instructions parser to its initial state.
     * @return void
     */
    public function reset() : void
    {
        $this->instructions = null;
    }
}