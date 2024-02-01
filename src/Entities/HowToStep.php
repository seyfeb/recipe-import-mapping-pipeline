<?php

namespace RecipeImportPipeline\Entities;

/**
 * A step in a series of steps.
 */
class HowToStep
{
    /**
     * @var array<string> Textual content of the step.
     */
    private array $Text;

    /**
     * @var array<HowToSection|HowToDirection|HowToStep> List of directions, tips, etc. for the step.
     */
    public array $ItemListElement;
}