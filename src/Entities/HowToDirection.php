<?php

namespace RecipeImportPipeline\Entities;

use RecipeImportPipeline\Exceptions\JsonMappingException;
use RecipeImportPipeline\Utilities\JsonMapper;
use RecipeImportPipeline\Utilities\TypeUtilities;

class HowToDirection extends BaseSchemaOrgEntity
{
    /**
     * @var array<string> Textual content of the direction.
     */
    public array $Text;

    /**
     * @var array<HowToSupply> List of supplies for the direction.
     */
    public array $Supply;

    /**
     * @param string[] $Text
     * @param HowToSupply[] $Supply
     */
    public function __construct(array $Text, array $Supply)
    {
        $this->Text = $Text;
        $this->Supply = $Supply;
    }


    /**
     * @inheritDoc
     */
    public static function fromJson(array $json) : ?HowToDirection {
        // Allowed value for `supply` is string array
        if(isset($json['text'])) {
            try {
                $text = JsonMapper::ExtractStringArray($json['text']);
            } catch (JsonMappingException) {
                return null;
            }
        }

        // Allowed values for `supply` is array of string | HowToSupply
        $supply = array();
        if(isset($json['supply'])) {
            $supplyArray = TypeUtilities::as_cleaned_array($json['supply']);

            foreach ($supplyArray as $item) {
                if (jsonMapper::isSchemaObject($item, 'HowToSupply', false)) {
                    if($suppl = HowToSupply::fromJson($item)) {
                        $supply[] = $suppl;
                        continue;
                    }
                }
                try {
                    $s = JsonMapper::ExtractString($item);
                    if($s){
                        $supply[] = new HowToSupply($s);
                    }
                } catch (JsonMappingException) {
                   // continue;
                }
            }
        }

        return new HowToDirection($text ?? [], $supply);
    }
}