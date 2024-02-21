<?php

namespace RecipeImportPipeline\Entities\SchemaOrg;

use RecipeImportPipeline\Entities\SchemaOrg\Utility\ISupply;
use RecipeImportPipeline\Exceptions\JsonMappingException;
use RecipeImportPipeline\Utilities\JsonMapper;

/**
 * A supply (e.g., ingredient).
 */
class HowToSupply extends BaseSchemaOrgEntity implements ISupply
{
    /**
     * @var string Name of the supply.
     */
    private string $Name;

    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @inheritDoc
     */
    protected static array $RequiredProperties = ['name'];


    /**
     * Creates a new `HowToSupply` instance
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->Name = $name;
    }

    /**
     * @inheritDoc
     */
    public static function fromJson(array $json) : ?HowToSupply {
        if(!HowToSupply::checkRequiredProperties($json)) {
            return null;
        }

        try {
            $name = JsonMapper::ExtractString($json['name']);
        }
        catch (JsonMappingException) {
            return null;
        }

        return new HowToSupply($name);
    }
}