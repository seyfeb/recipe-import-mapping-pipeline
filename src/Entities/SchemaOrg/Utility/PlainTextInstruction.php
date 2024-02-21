<?php

namespace RecipeImportPipeline\Entities\SchemaOrg\Utility;

use RecipeImportPipeline\Interfaces\Entities\IInstruction;

/**
 * An instruction that has only a string content.
 */
class PlainTextInstruction extends PlainText implements IInstruction
{
}