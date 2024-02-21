<?php

namespace RecipeImportPipeline\Parsers\JSON;

use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use RecipeImportPipeline\Entities\GenericJSON\JSONFloat;
use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\Keywords;
use RecipeImportPipeline\Entities\SchemaOrg\HowToSupply;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainText;
use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;

class KeywordsParser implements IJSONObjectParser
{
    /**
     * @var ?Keywords Local value of parsed Keywords.
     */
    private ?Keywords $keywords = null;

    /**
     * Accesses the value of the parsed Keywords.
     * @return Keywords|null The Keywords after parser has run.
     */
    public function getKeywords(): ?Keywords
    {
        return $this->keywords;
    }

    /**
     * @inheritDoc
     */
    public function handleInt(JSONInteger $value): void
    {
        // Not applicable for Keywords parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleBool(JSONBool $value): void
    {
        // Not applicable for Keywords parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleFloat(JSONFloat $value): void
    {
        // Not applicable for Keywords parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleString(JSONString $value): void
    {
        // Consider string as single supply
        $this->keywords = new Keywords([new PlainText($value->getValue())]);
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
            $this->keywords = new Keywords($arr);
        }
    }

    /**
     * @inheritDoc
     */
    public function handleObject(JSONObject $value): void
    {
        // Not applicable for Keywords parsing.
        // Implement if DefinedTerm is supported
    }

    /**
     * Resets the Keywords parser to its initial state.
     * @return void
     */
    public function reset() : void
    {
        $this->keywords = null;
    }
}