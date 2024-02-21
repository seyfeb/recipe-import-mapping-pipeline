<?php

namespace RecipeImportPipeline\Parsers\JSON;

use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use RecipeImportPipeline\Entities\GenericJSON\JSONFloat;
use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\Ingredients;
use RecipeImportPipeline\Entities\SchemaOrg\HowToSupply;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainText;
use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;

class IngredientsParser implements IJSONObjectParser
{
    /**
     * @var ?Ingredients Local value of parsed Ingredients.
     */
    private ?Ingredients $ingredients = null;

    /**
     * Accesses the value of the parsed Ingredients.
     * @return Ingredients|null The Ingredients after parser has run.
     */
    public function getIngredients(): ?Ingredients
    {
        return $this->ingredients;
    }

    /**
     * @inheritDoc
     */
    public function handleInt(JSONInteger $value): void
    {
        // Not applicable for Ingredients parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleBool(JSONBool $value): void
    {
        // Not applicable for Ingredients parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleFloat(JSONFloat $value): void
    {
        // Not applicable for Ingredients parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleString(JSONString $value): void
    {
        // Consider string as single supply
        $this->ingredients = new Ingredients([new PlainText($value->getValue())]);
    }

    /**
     * @inheritDoc
     */
    public function handleArray(JSONArray $value): void
    {
        $arr = [];

        foreach ($value->getValue() as $item){
            if($item instanceof JSONString){
                $arr[] = new PlainText($item->getValue());
            }
        }
        if(count($arr) > 0) {
            $this->ingredients = new Ingredients($arr);
        }
    }

    /**
     * @inheritDoc
     */
    public function handleObject(JSONObject $value): void
    {
        // Not applicable for Ingredients parsing.
        // Implement if DefinedTerm is supported
    }

    /**
     * Resets the Ingredients parser to its initial state.
     * @return void
     */
    public function reset() : void
    {
        $this->ingredients = null;
    }
}