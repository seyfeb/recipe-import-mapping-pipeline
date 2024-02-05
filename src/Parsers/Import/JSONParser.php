<?php

namespace RecipeImportPipeline\Parsers\Import;

use Exception;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use RecipeImportPipeline\Entities\GenericJSON\JSONFloat;
use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Interfaces\Entities\IJsonType;
use RecipeImportPipeline\Interfaces\Parsers\IParser;

/**
 * Parses JSON input into PHP object representation and a unified flattened representation.
 */
class JSONParser implements IParser
{
    /** @var FlattenJsonParser $flattenJsonParser JSON-flattening parser instance. */
    private FlattenJsonParser $flattenJsonParser;

    /**
     * Constructor for the JsonParser class.
     */
    public function __construct(FlattenJSONParser $flattenJSONParser) {
        $this->flattenJsonParser = $flattenJSONParser;
    }

    /**
     * Parse JSON input and return PHP objects and flattened representation.
     *
     * @param string $input The raw JSON input.
     * @return JSONArray Two-dimensional array containing PHP objects and flattened representation.
     * @throws Exception If the input is not valid JSON.
     */
    public function parse($input): JSONArray {
        // Decode the JSON input and
        // parse the JSON into a flattened representation
        $json = $this->flattenJsonParser->parse($input);

//        $json = json_decode($input, true);

        // Check if decoding was successful
        if ($json === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON input.');
        }

        // Convert the JSON to PHP objects
        return $this->jsonToPhpObjects($json);
    }

    /**
     * Convert JSON to PHP objects.
     *
     * @param array $json The JSON array to convert.
     * @return mixed The PHP objects converted from JSON.
     */
    private function jsonToPhpObjects(array $json): mixed {
        // Convert the JSON array to PHP objects
        return $this->convertJsonToPhpObjectsRecursive($json);
    }

    /**
     * Recursively convert JSON string to PHP objects.
     *
     * @param array $json The JSON string to convert.
     * @return mixed The PHP objects converted from JSON.
     */
    private function convertJsonToPhpObjectsRecursive(mixed $json): IJsonType {

        if (is_array($json)) {
            // Check if the JSON array is an object or an array
            if ($this->isAssociativeArray($json)) {
                // If it's an object, convert it to JSONObject
                $phpObject = new JSONObject();
                foreach ($json as $key => $value) {
                    $phpObject->$key = $this->convertJsonToPhpObjectsRecursive($value);
                }
            } else {
                // If it's an array, convert it to JSONArray
                $phpObject = new JSONArray();
                foreach ($json as $value) {
                    $phpObject->add($this->convertJsonToPhpObjectsRecursive($value));
                }
            }
        } else {
            // Convert scalar values to appropriate PHP object types
            if (is_string($json)) {
                $phpObject = new JSONString($json);
            } elseif (is_int($json)) {
                $phpObject = new JSONInteger($json);
            } elseif (is_float($json)) {
                $phpObject = new JSONFloat($json);
            } elseif (is_bool($json)) {
                $phpObject = new JSONBool($json);
            } else {
                // Handle null values
                $phpObject = null;
            }
        }
        return $phpObject;
    }

    /**
     * Check if an array is associative (object) or indexed (array).
     *
     * @param array $array The array to check.
     * @return bool True if the array is associative, false if it's indexed.
     */
    private function isAssociativeArray(array $array): bool {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }
}