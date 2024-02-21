<?php

namespace RecipeImportPipeline\Entities\SchemaOrg\Utility;

use RecipeImportPipeline\Interfaces\Entities\ISupply;

/**
 * A supply that has only a string content.
 */
class PlainTextSupply extends PlainText implements ISupply
{
}