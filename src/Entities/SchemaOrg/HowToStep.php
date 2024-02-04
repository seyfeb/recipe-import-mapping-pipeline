<?php

namespace RecipeImportPipeline\Entities\SchemaOrg;

use RecipeImportPipeline\Exceptions\JsonMappingException;
use RecipeImportPipeline\Utilities\JsonMapper;
use RecipeImportPipeline\Utilities\TypeUtilities;

/**
 * A step in a series of steps.
 */
class HowToStep extends BaseSchemaOrgEntity
{
    /**
     * @var array<string> Textual content of the step.
     */
    public array $Text;

    /**
     * @var array<HowToSection|HowToDirection|HowToStep> List of directions, tips, etc. for the step.
     */
    public array $ItemListElement;

    /**
     * HowToStep constructor.
     * @param array $Text
     * @param array $ItemListElement
     */
    public function __construct(array $Text, array $ItemListElement)
    {
        $this->Text = $Text;
        $this->ItemListElement = $ItemListElement;
    }

    /**
     * @inheritDoc
     */
    public static function fromJson(mixed $json): ?BaseSchemaOrgEntity
    {
        // At least one property should be set...
        if (!isset($json['text']) && !isset($json['itemListElement'])) {
            return null;
        }

        $text = array();
        // Extract text content
        if(isset($json['text'])) {
            try {
                $text = JsonMapper::ExtractStringArray($json['text']);
            } catch (JsonMappingException) {
                return null;
            }
        }

        // Extract itemListElement if present
        $itemListElement = [];
        if (isset($json['itemListElement'])) {
            $itemListJson = TypeUtilities::as_cleaned_array($json['itemListElement']);
            foreach ($itemListJson as $item) {
                // Check if the item is a HowToSection, HowToDirection, or HowToStep
                if (JsonMapper::isSchemaObject($item, 'HowToSection', false)) {
                    $itemListElement[] = HowToSection::fromJson($item);
                } elseif (JsonMapper::isSchemaObject($item, 'HowToDirection', false)) {
                    $itemListElement[] = HowToDirection::fromJson($item);
                } elseif (JsonMapper::isSchemaObject($item, 'HowToStep', false)) {
                    $itemListElement[] = HowToStep::fromJson($item);
                } else {
                    // If it's not any of the above, extract as string
                    try {
                        $itemText = JsonMapper::ExtractString($item);
                        if ($itemText) {
                            $itemListElement[] = $itemText;
                        }
                    } catch (JsonMappingException) {
                        // Ignore and continue
                    }
                }
            }
        }

        return new HowToStep($text, $itemListElement);
    }
}