<?php

use RecipeImportPipeline\Exceptions\JsonMappingException;

/**
 * Marker interface for recipe mappers from the unified to an export format.
 */
interface IRecipeMapper {
    /**
     * Retrieves the export format of the mapper.
     * @return ExportDataFormat
     */
    function getFormat() : ExportDataFormat;

    /**
     * Maps the unified JSON to the supported format.
     * @param string $json
     * @return mixed
     * @throws JsonMappingException
     */
    function map(string $json) : mixed;
}
