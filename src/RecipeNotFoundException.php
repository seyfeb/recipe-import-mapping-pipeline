<?php

namespace RecipeImportPipeline\Exceptions;

use Exception;

/**
 * Thrown if a requested recipe cannot be found.
 */
class RecipeNotFoundException extends Exception { }