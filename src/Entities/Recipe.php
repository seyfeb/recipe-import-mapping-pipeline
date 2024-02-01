<?php

namespace RecipeImportPipeline\Entities;

/**
 * A recipe object.
 */
class Recipe
{
    /**
     * @var string Unique identifier of the recipe.
     */
    private string $Id;

    /**
     * @var string Name of the recipe.
     */
    private string $Name;

    /**
     * @var array<HowToSupply> List of supplies for the recipe.
     */
    private array $Supply;

    /**
     * @var array<HowToSection|HowToStep> List of directions for the recipe.
     */
    private array $RecipeInstructions;
}