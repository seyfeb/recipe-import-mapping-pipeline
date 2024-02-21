<?php

namespace RecipeImportPipeline\Parsers\JSON;

use InvalidArgumentException;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use RecipeImportPipeline\Entities\GenericJSON\JSONFloat;
use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\HowToInstruction;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainTextInstruction;
use RecipeImportPipeline\Interfaces\Entities\IInstruction;
use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;

class InstructionParser implements IJSONObjectParser
{
    /**
     * @var IInstruction|null Local value of parsed instruction. Can be HowToDirection|HowToSection|HowToStep|PlaintextInstruction|null.
     */
    private mixed $instruction = null;

    /**
     * Accesses the value of the parsed instruction.
     * @return IInstruction|null The instruction after parser has run.
     */
    public function getInstruction() : mixed
    {
        return $this->instruction;
    }

    /**
     * @inheritDoc
     */
    public function handleInt(JSONInteger $value): void
    {
        // Not applicable for Instruction parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleBool(JSONBool $value): void
    {
        // Not applicable for Instruction parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleFloat(JSONFloat $value): void
    {
        // Not applicable for Instruction parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleString(JSONString $value): void
    {
        // Consider single string as the name of the instruction
        $this->instruction = new PlainTextInstruction($value->getValue());
    }

    /**
     * @inheritDoc
     */
    public function handleArray(JSONArray $value): void
    {
        // Not applicable for single Instruction parsing.
    }


    /**
     * @inheritDoc
     */
    public function handleObject(JSONObject $value): void
    {
        $howToDirectionParser = new HowToDirectionParser();

        if(isset($value['@type']) && $value['@type']->getValue() == 'HowToDirection'){
            $howToDirectionParser->reset();
            $value->parseWith($howToDirectionParser);
            $this->instruction = $howToDirectionParser->getDirection();
        }
        // TODO Other types of Instruction
        else{
            throw new InvalidArgumentException('InstructionParser received object with unsupported @type');
        }
    }

    /**
     * Resets the instruction parser to its initial state.
     * @return void
     */
    public function reset() : void
    {
        $this->instruction = null;
    }
}