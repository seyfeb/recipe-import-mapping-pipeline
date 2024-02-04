<?php

namespace RecipeImportPipeline\Services;

use RecipeImportPipeline\Exceptions\ImportException;
use RecipeImportPipeline\Exceptions\ParsingException;
use RecipeImportPipeline\Interfaces\Parsers\IParser;
use RecipeImportPipeline\Interfaces\Parsers\JSONParser;
use RecipeImportPipeline\Interfaces\Parsers\RecipeParser;

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
    public function importRecipe($input): void {
        // Step 1: Input Validation
        // Validate the input data to ensure it is in on of the expected formats supported by at least one of the parsers?

        // Step 2: Parsing
        // Parse the input data to extract relevant information about the recipe.

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

        // Step 3: Data Transformation
        // Transform the parsed data into a standardized format suitable for further processing.
        $jsonParser = new JsonParser();
        $jsonObjectRepresentation = $jsonParser->parse($output);

        // Step 4: Validation
        // Validate the parsed recipe data to ensure it meets certain criteria or constraints.

        $recipeParser = new RecipeParser();
        $recipeObjectRepresentation = $recipeParser->parse($jsonObjectRepresentation);

        // Step 5: Persistence
        // Persist the parsed recipe data to a storage system if necessary.
        if($recipeObjectRepresentation !== null){
            // Store initial raw data to disk
        }
        else{
            throw new ImportException('No recipe data could be extracted from provided source.');
        }

        // TODO: Error Handling
        // Handle any errors or exceptions that occur during the import process gracefully.

    }
}