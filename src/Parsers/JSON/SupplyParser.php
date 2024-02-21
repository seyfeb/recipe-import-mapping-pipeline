<?php

namespace RecipeImportPipeline\Parsers\JSON;

use InvalidArgumentException;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use RecipeImportPipeline\Entities\GenericJSON\JSONFloat;
use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\HowToSupply;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainTextSupply;
use RecipeImportPipeline\Interfaces\Entities\ISupply;
use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;

class SupplyParser implements IJSONObjectParser
{
    /**
     * @var ?ISupply Local value of parsed supply.
     */
    private ?ISupply $supply = null;

    /**
     * Accesses the value of the parsed supply.
     * @return ISupply|null The supply after parser has run.
     */
    public function getSupply() : ?ISupply
    {
        return $this->supply;
    }

    /**
     * @inheritDoc
     */
    public function handleInt(JSONInteger $value): void
    {
        // Not applicable for Supply parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleBool(JSONBool $value): void
    {
        // Not applicable for Supply parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleFloat(JSONFloat $value): void
    {
        // Not applicable for Supply parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleString(JSONString $value): void
    {
        // Consider single string as the name of the supply
        $this->supply = new PlainTextSupply($value->getValue());
    }

    /**
     * @inheritDoc
     */
    public function handleArray(JSONArray $value): void
    {
        // Not applicable for single Supply parsing.
    }


    /**
     * @inheritDoc
     */
    public function handleObject(JSONObject $value): void
    {
        if(isset($value['@type']) && $value['@type']->getValue() === 'HowToSupply'){
            $name = $value['Name']?->getValue();
            // TODO Add missing properties

            $this->supply = new HowToSupply($name);
        }
        else{
            // TODO we could try to extract supplies from the properties of the object
            error_log($value->toJSON());
            throw new InvalidArgumentException('SupplyParser received object without @type=HowToSupply');
        }
    }

    /**
     * Resets the supply parser to its initial state.
     * @return void
     */
    public function reset() : void
    {
        $this->supply = null;
    }
}