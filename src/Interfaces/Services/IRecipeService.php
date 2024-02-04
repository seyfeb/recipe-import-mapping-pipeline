<?php

use RecipeImportPipeline\Exceptions\RecipeNotFoundException;

/**
 * Interface for services handling recipe import.
 */
interface IRecipeService {
    /**
     * Import a recipe from the given input and return the parsed recipe data.
     *
     * @param mixed $input The input data containing the recipe.
     * @throws Exception If an error occurs during the import process.
     */
    public function importRecipe(mixed $input): void;

    /**
     * Export a recipe in a given format.
     *
     * @param string $id The identifier of the recipe to export.
     * @param ExportDataFormat $dataFormat The format in which to export the recipe.
     * @throws Exception Thrown if an error occurs during the export process.
     * @throws RecipeNotFoundException Thrown if recipe could not be found
     */
    public function exportRecipe(string $id, ExportDataFormat $dataFormat = ExportDataFormat::SchemaOrg): mixed;
}
