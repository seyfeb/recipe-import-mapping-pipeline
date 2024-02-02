<?php

namespace RecipeImportPipeline\Entities;

use RecipeImportPipeline\Exceptions\JsonMappingException;
use RecipeImportPipeline\Utilities\JsonMapper;

/**
 * A supply (e.g., ingredient).
 */
class HowToSupply extends BaseSchemaOrgEntity
{
    /**
     * @var string Name of the supply.
     */
    public string $Name;

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