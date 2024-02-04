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
        $json = json_decode($input, true);

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
            // Search for the property in the parent object and replace it with the identifier
            foreach ($this->flattenedRepresentation[$parentId] as $key => &$value) {
                if ($value === $json) {
                    $value = ['@id' => $objectId];
                    break;
                }
            }
            // important! ;)
            unset($value);
        }

        // Recursively process each property of the object
        foreach ($json as $key => $value) {
            if (is_array($value) && count($value) > 0 && !isset($value['@id'])) {
                // If the property is an object and does not have an @id, recursively process it
                $this->processJson($value, $objectId);
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
}