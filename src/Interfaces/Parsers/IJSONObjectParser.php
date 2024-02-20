<?php

namespace RecipeImportPipeline\Interfaces\Parsers;

use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use RecipeImportPipeline\Entities\GenericJSON\JSONFloat;
use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;

// Visitor pattern

/**
 * Interface for classes that parse JSON objects.
 */
interface IJSONObjectParser
{
    /**
     * Handles parsing of a JSON integer value.
     *
     * @param JSONInteger $value The JSON integer object to be parsed.
     * @return void
     */
    public function handleInt(JSONInteger $value): void;

    /**
     * Handles parsing of a JSON boolean value.
     *
     * @param JSONBool $value The JSON boolean object to be parsed.
     * @return void
     */
    public function handleBool(JSONBool $value): void;

    /**
     * Handles parsing of a JSON float value.
     *
     * @param JSONFloat $value The JSON float object to be parsed.
     * @return void
     */
    public function handleFloat(JSONFloat $value): void;

    /**
     * Handles parsing of a JSON string value.
     *
     * @param JSONString $value The JSON string object to be parsed.
     * @return void
     */
    public function handleString(JSONString $value): void;

    /**
     * Handles parsing of a JSON array value.
     *
     * @param JSONArray $value The JSON array object to be parsed.
     * @return void
     */
    public function handleArray(JSONArray $value): void;

    /**
     * Handles parsing of a JSON object value.
     *
     * @param JSONObject $value The JSON object to be parsed.
     * @return void
     */
    public function handleObject(JSONObject $value): void;
}
