<?php

namespace RecipeImportPipeline\Parsers\JSON;

use InvalidArgumentException;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use RecipeImportPipeline\Entities\GenericJSON\JSONFloat;
use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainText;
use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;

class NameParser implements IJSONObjectParser
{
    /**
     * @var PlainText|null Local value of parsed name.
     */
    private ?PlainText $name = null;

    /**
     * Accesses the value of the parsed name.
     * @return PlainText|null The name after parser has run.
     */
    public function getName() : ?PlainText
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function handleInt(JSONInteger $value): void
    {
        // Not applicable for Name parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleBool(JSONBool $value): void
    {
        // Not applicable for Name parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleFloat(JSONFloat $value): void
    {
        // Not applicable for Name parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleString(JSONString $value): void
    {
        // Consider single string as the name
        $this->name = new PlainText($value->getValue());
    }

    /**
     * @inheritDoc
     */
    public function handleArray(JSONArray $value): void
    {
        // Use first non-null and non-empty string as the name
        $str = current(array_filter($value->getValue(), function($e) {
            return $e instanceof JSONString &&
                !empty($e->getValue());
        }));

        if($str) {
            $this->name = new PlainText($str->getValue());
        }
    }


    /**
     * @inheritDoc
     */
    public function handleObject(JSONObject $value): void
    {
        // Not applicable for Name parsing.
    }

    /**
     * Resets the name parser to its initial state.
     * @return void
     */
    public function reset() : void
    {
        $this->name = null;
    }
}