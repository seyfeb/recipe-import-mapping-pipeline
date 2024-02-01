<?php

namespace RecipeImportPipeline\Utilities;

use RecipeImportPipeline\Exceptions\JsonMappingException;

class JsonMapper
{
    /**
     * Tries to extract a string value from the $value argument. Succeeds if argument is string, a number, ot an array
     * with a string or number as the first item.
     * @param mixed $value Value from which to extract a string
     * @param bool $allowNullOrUndefined If true, null values are allowed and returned as null.
     * @return string|null
     * @throws JsonMappingException Thrown if value can't be converted to string.
     */
    public static function ExtractString(mixed $value, bool $allowNullOrUndefined = false) : ?string {
        if ($value === null) {
            // Return null immediately if allowed
            if ($allowNullOrUndefined) return null;
            // Otherwise throw
            throw new JsonMappingException("Error mapping Json to string. Received ".gettype($value).".");
        }

        // No idea how to convert if...
        if (!is_string($value) && !is_numeric($value) && !is_array($value)) {
            throw new JsonMappingException("Error mapping Json to string. Received ".gettype($value).".");
        }

        // Supported types
        if (is_string($value)) {
            return $value;
        }
        if (is_numeric($value)) {
            return strval($value);
        }
        if (is_array($value) && sizeof($value) > 0 && is_string($value[0]) || is_numeric($value[0])) {
            return strval($value[0]);
        }
        throw new JsonMappingException("Error mapping Json to string. Received ".gettype($value).".");
	}

    /**
     * Tries to extract a string-array value from the $value argument. Succeeds if argument is string, a number, ot an
     * array of strings or numbers as items.
     * @param mixed $value Value from which to extract a string array
     * @param bool $allowNullOrUndefined If true, null values are allowed and returned as null.
     * @return array<string>|null
     * @throws JsonMappingException Thrown if value can't be converted to string.
     */
    public static function ExtractStringArray(mixed $value, bool $allowNullOrUndefined = false) : ?array {
        if ($value === null) {
            // Return null immediately if allowed
            if ($allowNullOrUndefined) return null;
            // Otherwise throw
            throw new JsonMappingException("Error mapping Json to string array. Received ".gettype($value).".");
        }

        // No idea how to convert if...
        if (!is_string($value) && !is_numeric($value) && !is_array($value)) {
            throw new JsonMappingException("Error mapping Json to string array. Received ".gettype($value).".");
        }

        // Supported types
        if (is_string($value) ) {
            return [$value];
        }
        if (is_numeric($value)) {
            return [strval($value)];
        }
        if (is_array($value)) {
            $ret = array_filter($value, function ($v, $k) { return is_string($v) || is_numeric($v); }, ARRAY_FILTER_USE_BOTH);
            return array_map(function ($v)  { return strval($v);}, $ret);
        }
        throw new JsonMappingException("Error mapping Json to string array. Received ".gettype($value).".");
	}
}