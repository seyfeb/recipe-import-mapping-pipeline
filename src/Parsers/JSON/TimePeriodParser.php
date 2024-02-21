<?php

namespace RecipeImportPipeline\Parsers\JSON;

use Exception;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use RecipeImportPipeline\Entities\GenericJSON\JSONFloat;
use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainText;
use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;
use RecipeImportPipeline\Utilities\ISO8601DurationHelper;

class TimePeriodParser implements IJSONObjectParser
{
    /**
     * @var PlainText|null Local value of parsed time.
     */
    private ?PlainText $timePeriod = null;

    private ISO8601DurationHelper $durationHelper;

    public function __construct()
    {
        $this->durationHelper = new ISO8601DurationHelper();
    }

    /**
     * Accesses the value of the parsed time.
     * @return PlainText|null The time after parser has run.
     */
    public function getTimePeriod() : ?PlainText
    {
        return $this->timePeriod;
    }

    /**
     * @inheritDoc
     */
    public function handleInt(JSONInteger $value): void
    {
        // Not applicable for Time parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleBool(JSONBool $value): void
    {
        // Not applicable for Time parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleFloat(JSONFloat $value): void
    {
        // Not applicable for Time parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleString(JSONString $value): void
    {
        // Consider single string as the time
        if($this->isValidISOPeriod($value->getValue()))
        {
            $this->timePeriod = new PlainText($value->getValue());
        }
    }

    /**
     * @inheritDoc
     */
    public function handleArray(JSONArray $value): void
    {
        // Use first non-null and non-empty string as the time
        $str = current(array_filter($value->getValue(), function($e) {
            return $e instanceof JSONString
                && !empty($e->getValue())
                // Time period validation
                && $this->isValidISOPeriod($e->getValue());
        }));

        if($str) {
            $this->timePeriod = new PlainText($str->getValue());
        }
    }


    /**
     * @inheritDoc
     */
    public function handleObject(JSONObject $value): void
    {
        // Not applicable for Time parsing.
    }

    /**
     * Resets the time parser to its initial state.
     * @return void
     */
    public function reset() : void
    {
        $this->timePeriod = null;
    }

    /**
     * Checks if value is a valid timestamp following the ISO8601 standard.
     * @param string $value Timestamp to check.
     * @return bool `true` if timestamp has correct format. `false` otherwise.
     */
    private function isValidISOPeriod(string $value) : bool
    {
        try{
            $this->durationHelper->parseDuration($value);
            return true;
        }
        catch (Exception){}

        return false;
    }
}