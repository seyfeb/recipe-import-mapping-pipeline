<?php

namespace RecipeImportPipeline\Tests\Unit\Entities;

use RecipeImportPipeline\Entities\HowToSupply;
use PHPUnit\Framework\TestCase;

class HowToSupplyTest extends TestCase
{
    public function testFromJsonWithValidData()
    {
        $json = [
            'name' => 'Test Supply'
        ];

        $supply = HowToSupply::fromJson($json);

        $this->assertInstanceOf(HowToSupply::class, $supply);
        $this->assertEquals('Test Supply', $supply->Name);
    }

    public function testFromJsonWithInvalidData()
    {
        // Test with missing 'name' key
        $json = [];
        $supply = HowToSupply::fromJson($json);
        $this->assertNull($supply);

        // Test with invalid 'name' value
        $json = ['name' => 123];
        $supply = HowToSupply::fromJson($json);
        $this->assertNull($supply);
    }
}
