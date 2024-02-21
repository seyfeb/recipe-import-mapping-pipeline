<?php

namespace RecipeImportPipeline\Entities\SchemaOrg\Utility;

use RecipeImportPipeline\Entities\SchemaOrg\BaseSchemaOrgEntity;

/**
 * A base element that has only a string content.
 */
class PlainText extends BaseSchemaOrgEntity
{
    /**
     * @var string Textual value.
     */
    private string $Value;

    public function getValue() : string
    {
        return $this->Value;
    }

    /**
     * BasePlainText constructor.
     * @param string $Value
     */
    public function __construct(string $Value)
    {
        $this->Value = $Value;
    }

    /**
     * @inheritDoc
     */
    public static function fromJson(array $json): ?BaseSchemaOrgEntity
    {
        // TODO: Implement fromJson() method.
        return null;
    }
}