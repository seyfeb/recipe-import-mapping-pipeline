<?php

namespace Entities;

use Utilities\TypeUtilities;

/**
 * A supply (e.g., ingredient).
 */
class HowToSupply extends BaseSchemaOrgEntity
{
    /**
     * @var string Name of the supply.
     */
    private string $Name;

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
        if(!isset($json['name'])) {
            return null;
        }
        $names = TypeUtilities::as_cleaned_array($json['name']);

        if(sizeof($names) == 0 || !is_string($names[0])) {
            return null;
        }

        return new HowToSupply($names[0]);
    }
}