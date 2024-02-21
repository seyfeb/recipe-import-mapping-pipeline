<?php

namespace RecipeImportPipeline\Entities\GenericJSON;

use RecipeImportPipeline\Interfaces\Entities\IJSONSerializable;
use RecipeImportPipeline\Interfaces\Entities\IJsonType;
use RecipeImportPipeline\Interfaces\Entities\IVisitableJSONObject;

abstract class BaseJSONObject implements IJsonType, IJSONSerializable, IVisitableJSONObject
{
}