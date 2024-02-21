<?php

namespace RecipeImportPipeline\Parsers\JSON;

use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use RecipeImportPipeline\Entities\GenericJSON\JSONFloat;
use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainText;
use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;

class TextsParser implements IJSONObjectParser
{
    /**
     * @var array<PlainText>|null Local value of parsed texts.
     */
    private ?array $texts = null;

    /**
     * Accesses the value of the parsed texts.
     * @return array<PlainText>|null The texts after parser has run.
     */
    public function getTexts() : ?array
    {
        return $this->texts;
    }

    /**
     * @inheritDoc
     */
    public function handleInt(JSONInteger $value): void
    {
        // Not applicable for Texts parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleBool(JSONBool $value): void
    {
        // Not applicable for Texts parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleFloat(JSONFloat $value): void
    {
        // Not applicable for Texts parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleString(JSONString $value): void
    {
        // Consider single string as the texts
        $this->texts = [new PlainText($value->getValue())];
    }

    /**
     * @inheritDoc
     */
    public function handleArray(JSONArray $value): void
    {
        // Filter invalid values
        $filtered_array = array_filter($value->getValue(), function($text) {
            return $text instanceof JSONString &&
                !empty($text->getValue());
        });

        // Map to array of PlainText
        $this->texts = array_map(function($text) { return new PlainText($text->getValue()); }, $filtered_array);
    }


    /**
     * @inheritDoc
     */
    public function handleObject(JSONObject $value): void
    {
        // Not applicable for Texts parsing.
    }

    /**
     * Resets the texts parser to its initial state.
     * @return void
     */
    public function reset() : void
    {
        $this->texts = null;
    }
}