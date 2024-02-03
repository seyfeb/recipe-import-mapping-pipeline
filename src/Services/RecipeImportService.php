<?php

namespace RecipeImportPipeline\Services;

use RecipeImportPipeline\Exceptions\ParsingException;
use RecipeImportPipeline\Interfaces\Parsers\IParser;

class RecipeImportService implements \IRecipeImportService
{
    /** @var IParser[] $parsers Parsers in the pipeline. */
    private $parsers = [];

    /**
     * Constructor for the Pipeline class.
     *
     * @param IParser[] $parsers Array of parsers to be added to the pipeline.
     */
    public function __construct(array $parsers) {
        foreach ($parsers as $parser) {
            $this->addParser($parser);
        }
    }

    /**
     * Add a parser to the pipeline.
     *
     * @param IParser $parser The parser to add.
     * @return void
     */
    public function addParser(IParser $parser) {
        $this->parsers[] = $parser;
    }

    /**
     * @inheritDoc
     */
    public function importRecipe($input): ?array {
        // Step 1: Input Validation
        // Validate the input data to ensure it is in the expected format and contains necessary information.

        // Step 2: Parsing
        // Parse the input data to extract relevant information about the recipe.

        // Step 3: Data Transformation
        // Transform the parsed data into a standardized format suitable for further processing.

        // Step 4: Validation
        // Validate the parsed recipe data to ensure it meets certain criteria or constraints.

        // Step 5: Persistence
        // Persist the parsed recipe data to a storage system if necessary.

        // Step 6: Return Result
        // Return the parsed recipe data as an array or an object representing the recipe.

        // Step 7: Error Handling
        // Handle any errors or exceptions that occur during the import process gracefully.


        // Example: This would try all parsers and return result of first successful
        $output = null;
        foreach ($this->parsers as $parser) {
            try {
                $output = $parser->parse($input);
                break;
            }
            catch (ParsingException){
            }
        }

        return $output;
    }
}