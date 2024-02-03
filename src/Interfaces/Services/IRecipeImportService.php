<?php

/**
 * Interface for services handling recipe import.
 */
interface IRecipeImportService {
    /**
     * Import a recipe from the given input and return the parsed recipe data.
     *
     * @param mixed $input The input data containing the recipe.
     * @return array|null The parsed recipe data.
     * @throws Exception If an error occurs during the import process.
     */
    public function importRecipe(mixed $input): ?array;
}
