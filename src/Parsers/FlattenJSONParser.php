<?php

namespace RecipeImportPipeline\Parsers;

use Exception;
use RecipeImportPipeline\Interfaces\Parsers\IParser;

/**
 * Parses JSON input into a unified flattened representation.
 */
class FlattenJSONParser implements IParser
{
    /** @var array $flattenedRepresentation Flattened representation of JSON objects. */
    private array $flattenedRepresentation = [];

    /** @var int $identifier Counter for generating unique identifiers. */
    private int $identifier = 1;

    /**
     * Parse JSON input and generate a flattened representation.
     *
     * @param string $input The raw JSON input.
     * @return array The flattened representation of JSON objects.
     * @throws Exception If the input is not valid JSON.
     */
    public function parse($input): array {
        // Decode the JSON input
        if(is_string($input)) {
            $json = json_decode($input, true);
        }
        else{
            $json = $input;
        }

        // Check if decoding was successful
        if ($json === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON input.');
        }

        // Process the JSON object
        $this->processJson($json);

        return $this->flattenedRepresentation;
    }

    /**
     * Recursively process the JSON object.
     *
     * @param array $json The JSON object to process.
     * @param int|null $parentId The ID of the parent object, if any.
     * @return void
     */
    private function processJson(array $json, ?int $parentId = null): void {
        // Generate a unique identifier for the current object
        $objectId = $this->generateIdentifier();

        // Add the object to the flattened representation
        $this->flattenedRepresentation[$objectId] = $json;

        // Add the identifier to the object
        $this->flattenedRepresentation[$objectId]['@id'] = $objectId;

        // Replace parent property with reference to extracted object
        if ($parentId !== null) {
            $this->replaceObjectInParent($this->flattenedRepresentation[$parentId], $json, $objectId);
        }

        // Recursively process each property of the object
        foreach ($json as $key => $value) {
            if (is_array($value)) {
                if ($this->isObject($value)) {
                    // If the property is an associative array (object), recursively process it
                    $this->processJson($value, $objectId);
                } else {
                    // If the property is an indexed array, iterate over its elements
                    foreach ($value as $index => $element) {
                        if (is_array($element) && $this->isObject($element)) {
                            // If the array element is an associative array (object), recursively process it
                            $this->processJson($element, $objectId);
                        }
                    }
                }
            }
        }
    }

    /**
     * Generate a unique identifier.
     *
     * @return int The generated identifier.
     */
    private function generateIdentifier(): int {
        return $this->identifier++;
    }

    /**
     * Check if an array is an object (associative array).
     *
     * @param array $array The array to check.
     * @return bool True if the array is an object, false otherwise.
     */
    private function isObject(array $array): bool {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }


    /**
     * Replace the object in the parent array with its an object with identifier pointing to the extracted object.
     *
     * @param array $haystack The JSON in which to search for the object to be replaced.
     * @param array $jsonToReplace The JSON object that should be replaced with the ID.
     * @param int $replacedObjectId The ID of the object to be replaced.
     * @return void
     */
    private function replaceObjectInParent(array &$haystack, array $jsonToReplace, int $replacedObjectId): void {
        // Found object, replace with id
        if($haystack === $jsonToReplace){
            $haystack = ['@id' => $replacedObjectId];
            return;
        }

        // Recursively search for the object in the parent array and replace it with the identifier
        foreach ($haystack as &$value) {
            if (is_array($value) && $value !== $haystack) {
                $this->replaceObjectInParent($value, $jsonToReplace, $replacedObjectId);
            } elseif ($value === $jsonToReplace) {
                $value = ['@id' => $replacedObjectId];
                break;
            }
        }
        unset($value);
    }
}