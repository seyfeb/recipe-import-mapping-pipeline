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


    /**
     * Check if an object is a JSON representation of a schema.org object
     *
     * The type of the object can be optionally checked using the second parameter.
     *
     * @param mixed $obj The object to check
     * @param string|null $type The type to check for. If null or '' no type check is performed
     * @param bool $checkContext If true, check for a present context entry
     * @param bool $uniqueType If false, also accept JSON objects that contain multiple types as @type.
     * @return bool true, if $obj is an object and optionally satisfies the type check
     */
    public static function isSchemaObject(mixed $obj, string $type = null, bool $checkContext = true, bool $uniqueType = true): bool {
        if (!is_array($obj)) {
            // Objects must be encoded as arrays in JSON
            return false;
        }

        if ($checkContext) {
            if (!isset($obj['@context']) || !preg_match('@^https?://schema\.org/?$@', $obj['@context'])) {
                // We have no correct context property
                return false;
            }
        }

        if (!isset($obj['@type'])) {
            // Objects must have a property @type
            return false;
        }

        // We have an object

        if ($type === null || $type === '') {
            // No typecheck was requested. So return true
            return true;
        }

        if (is_array($obj['@type'])) {
            if ($uniqueType) {
                if (count($obj['@type']) === 1 && $obj['@type'][0] === $type) {
                    return true;
                }
                return false;
            }

            $foundTypes = array_filter($obj['@type'], function ($x) use ($type) {
                return trim($x) === $type;
            });

            return count($foundTypes) > 0;
        }

        // Check if type matches
        return (strcmp($obj['@type'], $type) === 0);
    }
}