<?php

namespace RecipeImportPipeline\Parsers\JSON;

use RecipeImportPipeline\Entities\SchemaOrg\Recipe;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainText;
use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use RecipeImportPipeline\Entities\GenericJSON\JSONFloat;
use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;

class RecipeParser implements IJSONObjectParser
{
    /**
     * @var ?Recipe Local value of parsed recipe.
     */
    private ?Recipe $recipe = null;

    private TimePeriodParser $periodParser;
    private IngredientsParser $ingredientsParser;
    private InstructionsParser $instructionsParser;
    private KeywordsParser $keywordsParser;
    private NameParser $nameParser;
    private SuppliesParser $suppliesParser;
    private TimestampParser $timestampParser;

    /**
     * Constructs new RecipeParser
     */
    public function __construct()
    {
        $this->periodParser = new TimePeriodParser();
        $this->ingredientsParser = new IngredientsParser();
        $this->instructionsParser = new InstructionsParser();
        $this->keywordsParser = new KeywordsParser();
        $this->nameParser = new NameParser();
        $this->suppliesParser = new SuppliesParser();
        $this->timestampParser = new TimestampParser();
    }


    /**
     * Accesses the value of the parsed recipe.
     * @return Recipe|null The recipe after parser has run.
     */
    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    /**
     * @inheritDoc
     */
    function handleInt(JSONInteger $value) : void{
        // Not applicable for Recipe parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleBool(JSONBool $value): void
    {
        // Not applicable for Recipe parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleFloat(JSONFloat $value): void
    {
        // Not applicable for Recipe parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleString(JSONString $value): void
    {
        // Not applicable for Recipe parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleArray(JSONArray $value): void
    {
        // Not applicable for Recipe parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleObject(JSONObject $value): void
    {
        // Extracting properties from the JSON object
        $properties = $value->getValue();

        // Variables to hold parsed values
        $identifier = null;
        $name = null;
        $dateCreated = null;
        $keywords = null;
        $totalTime = null;
        $recipeIngredient = null;
        $recipeInstructions = null;
        $supply = null;

        // Check each property and parse accordingly
        foreach ($properties as $key => $property) {
            switch ($key) {
                case 'identifier':
                    $identifier = new PlainText($property->getValue());
                    // TODO Validation
                    break;
                case 'name':
                    $this->nameParser->reset();
                    $property->parseWith($this->nameParser);
                    $name =  $this->nameParser->getName();
                    break;
                case 'dateCreated':
                    $this->timestampParser->reset();
                    $property->parseWith($this->timestampParser);
                    $dateCreated = new PlainText($property->getValue());
                    break;
                case 'keywords':
                    $this->keywordsParser->reset();
                    $property->parseWith($this->keywordsParser);
                    $keywords = $this->keywordsParser->getKeywords();
                    break;
                case 'totalTime':
                    $this->periodParser->reset();
                    $property->parseWith($this->periodParser);
                    $totalTime = $this->periodParser->getTimePeriod();
                    break;
                case 'recipeIngredient':
                    $this->ingredientsParser->reset();
                    $property->parseWith($this->ingredientsParser);
                    $recipeIngredient = $this->ingredientsParser->getIngredients();
                    break;
                case 'recipeInstructions':
                    $this->instructionsParser->reset();
                    $property->parseWith($this->instructionsParser);
                    $recipeInstructions = $this->instructionsParser->getInstructions();
                    break;
                case 'supply':
                    $this->suppliesParser->reset();
                    $property->parseWith($this->suppliesParser);
                    $supply = $this->suppliesParser->getSupplies();
                    break;
                default:
                    // Handle unknown properties
                    break;
            }
        }
        $this->recipe = new Recipe($identifier, $name, $dateCreated, $keywords, $totalTime, $recipeIngredient,
            $recipeInstructions, $supply);
    }
}