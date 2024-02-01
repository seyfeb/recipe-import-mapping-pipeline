<?php

namespace RecipeImportPipeline\Entities;

abstract class BaseSchemaOrgEntity
{
    /**
     * Parses the provided JSON array as a schema.org object
     * @param array $json
     * @return ?BaseSchemaOrgEntity
     */
    public static abstract function fromJson(array $json) : ?BaseSchemaOrgEntity;
}