<?php

namespace RecipeImportPipeline\Entities\SchemaOrg;

use RecipeImportPipeline\Exceptions\JsonMappingException;
use RecipeImportPipeline\Utilities\JsonMapper;
use RecipeImportPipeline\Utilities\TypeUtilities;

/**
 * A recipe object.
 */
class Recipe extends BaseSchemaOrgEntity
{
    /**
     * @inheritDoc
     */
    protected static array $RequiredProperties = ['name'];

    /**
     * @var string Unique identifier of the recipe.
     */
    private string $Id;

    /**
     * @var string Name of the recipe.
     */
    public string $Name;

    /**
     * @var array<HowToSupply> List of supplies for the recipe.
     */
    public array $Supply;

    /**
     * @var array<HowToSection|HowToStep> List of directions for the recipe.
     */
    public array $RecipeInstructions;

    /**
     * Recipe constructor.
     * @param string $Id
     * @param string $Name
     * @param array $Supply
     * @param array $RecipeInstructions
     */
    public function __construct(string $Id, string $Name, array $Supply, array $RecipeInstructions)
    {
        $this->Id = $Id;
        $this->Name = $Name;
        $this->Supply = $Supply;
        $this->RecipeInstructions = $RecipeInstructions;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->Id;
    }

    /**
     * @inheritDoc
     */
    public static function fromJson(array $json): ?BaseSchemaOrgEntity
    {
        // Validate required fields
        if (!Recipe::checkRequiredProperties($json)) {
            return null;
        }

        try {
            // Extract Id and Name
            $id = JsonMapper::ExtractString($json['identifier']);
            $name = JsonMapper::ExtractString($json['name']);

            // Extract supplies
            $supply = [];
            if (isset($json['recipeIngredient'])) {
                $supply = JsonMapper::ExtractStringArray($json['recipeIngredient']);
            }

            // Extract recipe instructions
            $recipeInstructions = [];
            if (isset($json['recipeInstructions'])) {
                $instructions = TypeUtilities::as_cleaned_array($json['recipeInstructions']);
                foreach ($instructions as $instruction) {
                    if (JsonMapper::isSchemaObject($instruction, 'HowToSection', false)) {
                        $recipeInstructions[] = HowToSection::fromJson($instruction);
                    } elseif (JsonMapper::isSchemaObject($instruction, 'HowToStep', false)) {
                        $recipeInstructions[] = HowToStep::fromJson($instruction);
                    } elseif (JsonMapper::isSchemaObject($instruction, 'HowToDirection', false)) {
                        $recipeInstructions[] = HowToDirection::fromJson($instruction);
                    } else {
                        $recipeInstructions[] = $instruction;
                    }
                }
            }

            return new Recipe($id, $name, $supply, $recipeInstructions);
        } catch (JsonMappingException) {
            return null;
        }
    }
}