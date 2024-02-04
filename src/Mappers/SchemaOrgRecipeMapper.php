<?php

namespace RecipeImportPipeline\Mappers;

use ExportDataFormat;
use IRecipeMapper;
use RecipeImportPipeline\Entities\SchemaOrg\BaseSchemaOrgEntity;
use RecipeImportPipeline\Entities\SchemaOrg\Recipe;
use RecipeImportPipeline\Exceptions\JsonMappingException;

class SchemaOrgRecipeMapper implements IRecipeMapper
{
    /**
     * @inheritDoc
     */
    function getFormat(): ExportDataFormat
    {
        return ExportDataFormat::SchemaOrg;
    }

    /**
     * @inheritDoc
     */
    function map(string $json): ?BaseSchemaOrgEntity
    {
        $json = json_decode($json);

        if($json == null && json_last_error() !== JSON_ERROR_NONE){
            throw new JsonMappingException('Unable to decode JSON string');
        }

        return Recipe::fromJson($json);
    }
}