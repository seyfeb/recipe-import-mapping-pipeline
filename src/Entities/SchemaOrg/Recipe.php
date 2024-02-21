<?php

namespace RecipeImportPipeline\Entities\SchemaOrg;

use RecipeImportPipeline\Entities\SchemaOrg\Utility\Ingredients;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\Instructions;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\Keywords;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainText;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\Supplies;

/**
 * A recipe object.
 */
class Recipe {
    /** @var PlainText|null $identifier The identifier of the recipe. */
    private ?PlainText $identifier;

    /** @var PlainText|null $name The name of the recipe. */
    private ?PlainText $name;

    /** @var PlainText|null $dateCreated The creation date of the recipe. */
    private ?PlainText $dateCreated;

    /** @var Keywords|null $keywords The keywords associated with the recipe. */
    private ?Keywords $keywords;

    /** @var PlainText|null $totalTime The total time required for the recipe. */
    private ?PlainText $totalTime;

    /** @var Ingredients|null $recipeIngredient The ingredients needed for the recipe. */
    private ?Ingredients $recipeIngredient;

    /** @var Instructions|null $recipeInstructions The instructions for preparing the recipe. */
    private ?Instructions $recipeInstructions;

    /** @var Supplies|null $supply The supplies required for the recipe. */
    private ?Supplies $supply;

    /**
     * Constructor for the Recipe class.
     *
     * @param PlainText|null $identifier The identifier of the recipe.
     * @param PlainText|null $name The name of the recipe.
     * @param PlainText|null $dateCreated The creation date of the recipe.
     * @param Keywords|null $keywords The keywords associated with the recipe.
     * @param PlainText|null $totalTime The total time required for the recipe.
     * @param Ingredients|null $recipeIngredient The ingredients needed for the recipe.
     * @param Instructions|null $recipeInstructions The instructions for preparing the recipe.
     * @param Supplies|null $supply The supplies required for the recipe.
     */
    public function __construct(
        ?PlainText $identifier,
        ?PlainText $name,
        ?PlainText $dateCreated,
        ?Keywords $keywords,
        ?PlainText $totalTime,
        ?Ingredients $recipeIngredient,
        ?Instructions $recipeInstructions,
        ?Supplies $supply,
    ) {
        $this->identifier = $identifier;
        $this->name = $name;
        $this->dateCreated = $dateCreated;
        $this->keywords = $keywords;
        $this->totalTime = $totalTime;
        $this->recipeIngredient = $recipeIngredient;
        $this->recipeInstructions = $recipeInstructions;
        $this->supply = $supply;
    }

    /**
     * Get the identifier of the recipe.
     *
     * @return PlainText|null The identifier of the recipe.
     */
    public function getIdentifier(): ?PlainText {
        return $this->identifier;
    }

    /**
     * Get the name of the recipe.
     *
     * @return PlainText|null The name of the recipe.
     */
    public function getName(): ?PlainText {
        return $this->name;
    }

    /**
     * Get the creation date of the recipe.
     *
     * @return PlainText|null The creation date of the recipe.
     */
    public function getDateCreated(): ?PlainText {
        return $this->dateCreated;
    }

    /**
     * Get the keywords associated with the recipe.
     *
     * @return Keywords|null The keywords associated with the recipe.
     */
    public function getKeywords(): ?Keywords {
        return $this->keywords;
    }

    /**
     * Get the total time required for the recipe.
     *
     * @return PlainText|null The total time required for the recipe.
     */
    public function getTotalTime(): ?PlainText {
        return $this->totalTime;
    }

    /**
     * Get the ingredients needed for the recipe.
     *
     * @return Ingredients|null The ingredients needed for the recipe.
     */
    public function getRecipeIngredient(): ?Ingredients {
        return $this->recipeIngredient;
    }

    /**
     * Get the instructions for preparing the recipe.
     *
     * @return Instructions|null The instructions for preparing the recipe.
     */
    public function getRecipeInstructions(): ?Instructions {
        return $this->recipeInstructions;
    }

    /**
     * Get the supplies required for the recipe.
     *
     * @return Supplies|null The supplies required for the recipe.
     */
    public function getSupply(): ?Supplies {
        return $this->supply;
    }
}
