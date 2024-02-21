<?php

namespace RecipeImportPipeline\Entities\SchemaOrg;

use RecipeImportPipeline\Entities\SchemaOrg\Utility\IInstruction;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\PlainText;
use RecipeImportPipeline\Entities\SchemaOrg\Utility\Supplies;
use RecipeImportPipeline\Exceptions\JsonMappingException;
use RecipeImportPipeline\Utilities\JsonMapper;
use RecipeImportPipeline\Utilities\TypeUtilities;

class HowToDirection extends BaseSchemaOrgEntity implements IInstruction
{
    /**
     * @var string[] Textual content of the direction.
     */
    private array $Text;

    /**
     * @var Supplies|null List of supplies for the direction.
     */
    private ?Supplies $Supply;

    /**
     * @return PlainText[]
     */
    public function getText(): array
    {
        return $this->Text;
    }

    public function getSupply(): ?Supplies
    {
        return $this->Supply;
    }

    /**
     * @param string[] $text
     * @param Supplies|null $supply
     */
    public function __construct(array $text, ?Supplies $supply)
    {
        $this->Text = $text;
        $this->Supply = $supply;
    }

    /**
     * @inheritDoc
     */
    public static function fromJson(array $json) : ?HowToDirection {
        return null;
//        // Allowed value for `supply` is string array
//        if(isset($json['text'])) {
//            try {
//                $text = JsonMapper::ExtractStringArray($json['text']);
//            } catch (JsonMappingException) {
//                return null;
//            }
//        }
//
//        // Allowed values for `supply` is array of string | HowToSupply
//        $supply = array();
//        if(isset($json['supply'])) {
//            $supplyArray = TypeUtilities::as_cleaned_array($json['supply']);
//
//            foreach ($supplyArray as $item) {
//                if (jsonMapper::isSchemaObject($item, 'HowToSupply', false)) {
//                    if($suppl = HowToSupply::fromJson($item)) {
//                        $supply[] = $suppl;
//                        continue;
//                    }
//                }
//                try {
//                    $s = JsonMapper::ExtractString($item);
//                    if($s){
//                        $supply[] = new HowToSupply($s);
//                    }
//                } catch (JsonMappingException) {
//                   // continue;
//                }
//            }
//        }
//
//        return new HowToDirection($text ?? [], $supply);
    }
}