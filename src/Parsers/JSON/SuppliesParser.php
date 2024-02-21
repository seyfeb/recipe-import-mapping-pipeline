<?php

namespace RecipeImportPipeline\Parsers\JSON;

use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use RecipeImportPipeline\Entities\GenericJSON\JSONFloat;
use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainTextSupply;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\Supplies;
use RecipeImportPipeline\Entities\SchemaOrg\HowToSupply;
use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;

class SuppliesParser implements IJSONObjectParser
{
    /**
     * @var ?Supplies Local value of parsed supplies.
     */
    private ?Supplies $supplies = null;

    /**
     * Accesses the value of the parsed supplies.
     * @return Supplies|null The supplies after parser has run.
     */
    public function getSupplies(): ?Supplies
    {
        return $this->supplies;
    }

    /**
     * @inheritDoc
     */
    public function handleInt(JSONInteger $value): void
    {
        // Not applicable for Supplies parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleBool(JSONBool $value): void
    {
        // Not applicable for Supplies parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleFloat(JSONFloat $value): void
    {
        // Not applicable for Supplies parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleString(JSONString $value): void
    {
        // Consider string as single supply
        $this->supplies = new Supplies([new PlainTextSupply($value->getValue())]);
    }

    /**
     * @inheritDoc
     */
    public function handleArray(JSONArray $value): void
    {
        $supplyParser = new SupplyParser();
        $arr = [];

        foreach ($value->getValue() as $item){
            $supplyParser->reset();
            $item->parseWith($supplyParser);
            $parsedSupply = $supplyParser->getSupply();
            if($parsedSupply !== null) {
                $arr[] = $parsedSupply;
            }
        }
        $this->supplies = new Supplies($arr);
    }

    /**
     * @inheritDoc
     */
    public function handleObject(JSONObject $value): void
    {
        // Try treating as single HowToSupply
        $supplyParser = new SupplyParser();
        $value->parseWith($supplyParser);

        $parsedSupply = $supplyParser->getSupply();
        if($parsedSupply !== null) {
            $this->supplies = new Supplies([$supplyParser->getSupply()]);
        }
    }

    /**
     * Resets the supplies parser to its initial state.
     * @return void
     */
    public function reset() : void
    {
        $this->supplies = null;
    }
}