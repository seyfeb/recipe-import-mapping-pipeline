<?php

namespace RecipeImportPipeline\Entities\SchemaOrg;

abstract class BaseSchemaOrgEntity
{
    /**
     * @var array<string> List of properties that have to be set on the entity.
     */
    protected static array $RequiredProperties = array();

    /**
     * Parses the provided JSON array as a schema.org object
     * @param array $json
     * @return ?BaseSchemaOrgEntity
     */
    public abstract static function fromJson(array $json) : ?BaseSchemaOrgEntity;

    /**
     * Checks if all required properties for the schema.org object are set.
     * @param array $json Object to be checked.
     * @return bool `true` if all properties are set. `false` otherwise.
     */
    public static function checkRequiredProperties(array $json) : bool {
        foreach (static::$RequiredProperties as $prop) {
            if(!isset($json[$prop])){
                return false;
            }
        }
        return true;
    }
}