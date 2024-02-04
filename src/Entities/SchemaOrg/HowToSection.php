<?php

namespace RecipeImportPipeline\Entities\SchemaOrg;

use RecipeImportPipeline\Exceptions\JsonMappingException;
use RecipeImportPipeline\Utilities\JsonMapper;
use RecipeImportPipeline\Utilities\TypeUtilities;

/**
 * A section combining multiple steps or subsections.
 */
class HowToSection extends BaseSchemaOrgEntity
{
    /**
     * @var array<string> Textual description of the section.
     */
    public array $Description;

    /**
     * @var array<HowToDirection|HowToSection|HowToStep> List of directions, tips, etc. for the step.
     */
    public array $ItemListElement;

    /**
     * HowToSection constructor.
     * @param array<string> $Description
     * @param array<HowToDirection|HowToSection|HowToStep|string> $ItemListElement
     */
    public function __construct(array $Description, array $ItemListElement)
    {
        $this->Description = $Description;
        $this->ItemListElement = $ItemListElement;
    }

    /**
     * @inheritDoc
     */
    public static function fromJson(mixed $json): ?BaseSchemaOrgEntity
    {
        // Validate the JSON structure
        if (!is_array($json)) {
            return null;
        }

        try {
            // Extract the description
            $description = [];
            if (isset($json['description'])) {
                $description = JsonMapper::ExtractStringArray($json['description']);
            }

            // Extract the itemListElement
            $itemListElement = [];
            $itemList = TypeUtilities::as_cleaned_array($json['itemListElement']);
            foreach ($itemList as $item) {
                if (JsonMapper::isSchemaObject($item, 'HowToDirection', false)) {
                    $itemListElement[] = HowToDirection::fromJson($item);
                } elseif (JsonMapper::isSchemaObject($item, 'HowToSection', false)) {
                    $itemListElement[] = HowToSection::fromJson($item);
                } elseif (JsonMapper::isSchemaObject($item, 'HowToStep', false)) {
                    $itemListElement[] = HowToStep::fromJson($item);
                } elseif (is_string($item)) {
                    $itemListElement[] = $item;
                }
            }

            return new HowToSection($description, $itemListElement);
        } catch (JsonMappingException) {
            return null;
        }
    }
}