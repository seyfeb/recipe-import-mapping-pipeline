<?php

namespace RecipeImportPipeline\Parsers\JSON;

use InvalidArgumentException;
use RecipeImportPipeline\Entities\GenericJSON\JSONArray;
use RecipeImportPipeline\Entities\GenericJSON\JSONBool;
use RecipeImportPipeline\Entities\GenericJSON\JSONFloat;
use RecipeImportPipeline\Entities\GenericJSON\JSONInteger;
use RecipeImportPipeline\Entities\GenericJSON\JSONObject;
use RecipeImportPipeline\Entities\GenericJSON\JSONString;
use RecipeImportPipeline\Entities\SchemaOrg\HowToDirection;
use RecipeImportPipeline\Interfaces\Parsers\IJSONObjectParser;

class HowToDirectionParser implements IJSONObjectParser
{
    /**
     * @var ?HowToDirection Local value of parsed direction.
     */
    private ?HowToDirection $direction = null;

    /**
     * Accesses the value of the parsed direction.
     * @return HowToDirection|null The direction after parser has run.
     */
    public function getDirection() : ?HowToDirection
    {
        return $this->direction;
    }

    /**
     * @inheritDoc
     */
    public function handleInt(JSONInteger $value): void
    {
        // Not applicable for Direction parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleBool(JSONBool $value): void
    {
        // Not applicable for Direction parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleFloat(JSONFloat $value): void
    {
        // Not applicable for Direction parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleString(JSONString $value): void
    {
        // Not applicable for Direction parsing.
    }

    /**
     * @inheritDoc
     */
    public function handleArray(JSONArray $value): void
    {
        // Not applicable for Direction parsing.
    }


    /**
     * @inheritDoc
     */
    public function handleObject(JSONObject $value): void
    {
        $suppliesParser = new SuppliesParser();
        $textsParser = new TextsParser();

        $objectValue = $value->getValue();

        if(isset($value['@type'])
            && $objectValue['@type']->getValue() === 'HowToDirection')
        {
            $suppliesParser->reset();

            // Get texts
            $text = null;
            if(isset($value['Text'])){
                $value['Text']->parseWith($textsParser);
                $text = $textsParser->getTexts();
            }

            // Get supply
            $supplies = null;
            if(isset($value['Supply'])) {
                $value['Supply']->parseWith($suppliesParser);
                $supplies = $suppliesParser->getSupplies();
            }
            // TODO Add missing properties

            $this->direction = new HowToDirection($text, $supplies);
        }
        else
        {
            throw new InvalidArgumentException('DirectionParser received object without @type=HowToDirection');
        }
    }

    /**
     * Resets the direction parser to its initial state.
     * @return void
     */
    public function reset() : void
    {
        $this->direction = null;
    }
}