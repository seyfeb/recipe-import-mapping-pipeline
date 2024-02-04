<?php

namespace RecipeImportPipeline\Services;

use Exception;
use ExportDataFormat;
use IRecipeMapper;
use IRecipeService;
use RecipeImportPipeline\Exceptions\ImportException;
use RecipeImportPipeline\Exceptions\ParsingException;
use RecipeImportPipeline\Exceptions\RecipeExportException;
use RecipeImportPipeline\Exceptions\RecipeNotFoundException;
use RecipeImportPipeline\Interfaces\Parsers\IParser;
use RecipeImportPipeline\Interfaces\Parsers\IRawParser;
use RecipeImportPipeline\Interfaces\Parsers\RecipeParser;
use RecipeImportPipeline\Parsers\FlattenJSONParser;
use RecipeImportPipeline\Parsers\JSONParser;

class RecipeService implements IRecipeService
{
    /** @var IRawParser[] $rawParsers Parsers in the pipeline. */
    private array $rawParsers = [];

    /** @var JSONParser $flattenJSONParser Parser for parsing the JSON input into PHP objects. */
    private JSONParser $jsonParser;

    /** @var RecipeParser $recipeParser Parser extracting recipe objects */
    private RecipeParser $recipeParser;

    /** @var IRecipeMapper[] $rawParsers Parsers in the pipeline. */
    private array $recipeMappers;

    /**
     * Constructor for the Pipeline class.
     *
     * @param IRawParser[] $rawParsers Array of parsers to be added to the pipeline.
     * @param JSONParser $jsonParser JSON parser for converting JSON to PHP object representation.
     * @param FlattenJSONParser $flattenJSONParser Parser for flattening the JSON structure.
     * @param IRecipeMapper[] $recipeMappers Array of export mappers.
     */
    public function __construct(
        array             $rawParsers,
        JSONParser        $jsonParser,
        FlattenJSONParser $flattenJSONParser,
        array             $recipeMappers) {
        foreach ($rawParsers as $parser) {
            $this->addParser($parser);
        }
        $this->$jsonParser = $jsonParser;
        $this->$flattenJSONParser = $flattenJSONParser;
        $this->recipeMappers = $recipeMappers;
    }

    /**
     * Add a parser to the pipeline.
     *
     * @param IParser $parser The parser to add.
     * @return void
     */
    public function addParser(IParser $parser): void
    {
        $this->rawParsers[] = $parser;
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
        foreach ($this->rawParsers as $parser) {
            try {
                $output = $parser->parse($input);
                break;
            }
            catch (ParsingException){
            }
        }

        // Step 3: Data Transformation
        // Transform the parsed data into a standardized format suitable for further processing.
        $jsonObjectRepresentation = $this->jsonParser->parse($output);

        // Step 4: Validation
        // Validate the parsed recipe data to ensure it meets certain criteria or constraints.
        $recipeObjectRepresentation = $this->recipeParser->parse($jsonObjectRepresentation);

        // Step 5: Persistence
        // Persist the parsed recipe data to a storage system if necessary.
        if($recipeObjectRepresentation !== null){
            // TODO
            // Store initial raw data to disk
        }
        else{
            throw new ImportException('No recipe data could be extracted from provided source.');
        }

        // TODO: Error Handling
        // Handle any errors or exceptions that occur during the import process gracefully.
    }

    /**
     * @inheritDoc
     */
    public function exportRecipe(string $id, ExportDataFormat $dataFormat = ExportDataFormat::SchemaOrg) : mixed
    {
        // Fetch recipe data
        $recipeJson = null;
        try{
            // TODO Fetch unified-JSON recipe data from X
        } catch (Exception){
            throw new RecipeNotFoundException('Requested recipe with id ' . $id . ' could not be found.');
        }

        // Find the correct mapper for the requested output format
        $mapper = null;
        foreach ($this->recipeMappers as $mappy){
            if($mappy->getFormat() === $dataFormat){
                $mapper = $mappy;
            }
        }

        // Check if mapper has been found
        if($mapper === null){
            throw new RecipeExportException('No mapper for requested export format found.');
        }

        // Execute mapping and return result
        return $mapper->map($recipeJson);
    }
}