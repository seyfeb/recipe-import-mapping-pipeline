<?php

namespace RecipeImportPipeline\Entities;

/**
 * A section combining multiple steps or subsections.
 */
class HowToSection
{
    /**
     * @var array<string> Textual description of the section.
     */
    private array $Description;

    /**
     * @var array<HowToDirection|HowToSection|HowToStep> List of directions, tips, etc. for the step.
     */
    public array $ItemListElement;

}