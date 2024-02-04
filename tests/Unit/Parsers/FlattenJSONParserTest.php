<?php

namespace RecipeImportPipeline\Tests\Unit\Parsers;

use RecipeImportPipeline\Parsers\FlattenJSONParser;
use PHPUnit\Framework\TestCase;

class FlattenJSONParserTest extends TestCase
{
    public function testParseFlatJson() {
        $input = '{
            "@context": "https://schema.org",
            "@type": "Recipe",
            "name": "Baked bananas",
            "author": {
                "@type": "Person",
                "name": "Santa Claus"
            }
        }';

        $expectedOutput = [
            1 => [
                "@context" => "https://schema.org",
                "@type" => "Recipe",
                "name" => "Baked bananas",
                "author" => [
                    "@id" => 2
                ],
                "@id" => 1
            ],
            2 => [
                "@type" => "Person",
                "name" => "Santa Claus",
                "@id" => 2
            ]
        ];

        $parser = new FlattenJsonParser();
        $output = $parser->parse($input);
        $this->assertEquals($expectedOutput, $output);
    }


    public function testParseFlatJsonWithArray() {
        $input = '{
            "@context": "https://schema.org",
            "@type": "Recipe",
            "name": "Baked bananas",
            "author": [
                {
                    "@type": "Person",
                    "name": "Santa Claus"
                },
                "Some elves"
            ]
        }';

        $expectedOutput = [
            1 => [
                "@context" => "https://schema.org",
                "@type" => "Recipe",
                "name" => "Baked bananas",
                "author" => [
                    [
                        "@id" => 2
                    ],
                    "Some elves"
                ],
                "@id" => 1
            ],
            2 => [
                "@type" => "Person",
                "name" => "Santa Claus",
                "@id" => 2
            ]
        ];

        $parser = new FlattenJsonParser();
        $output = $parser->parse($input);
        $this->assertEquals($expectedOutput, $output);
    }

    public function testParseNestedJson() {
        $input = '{
            "@context": "https://schema.org",
            "@type": "Recipe",
            "name": "Baked bananas",
            "author": {
                "@type": "Person",
                "name": "Santa Claus",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "1 North Pole",
                    "addressLocality": "North Pole",
                    "addressRegion": "Arctic",
                    "postalCode": "00000",
                    "addressCountry": "Earth"
                }
            }
        }';

        $expectedOutput = [
            1 => [
                "@context" => "https://schema.org",
                "@type" => "Recipe",
                "name" => "Baked bananas",
                "author" => [
                    "@id" => 2
                ],
                "@id" => 1
            ],
            2 => [
                "@type" => "Person",
                "name" => "Santa Claus",
                "address" => [
                    "@id" => 3
                ],
                "@id" => 2
            ],
            3 => [
                "@type" => "PostalAddress",
                "streetAddress" => "1 North Pole",
                "addressLocality" => "North Pole",
                "addressRegion" => "Arctic",
                "postalCode" => "00000",
                "addressCountry" => "Earth",
                "@id" => 3
            ]
        ];

        $parser = new FlattenJsonParser();
        $output = $parser->parse($input);
        $this->assertEquals($expectedOutput, $output);
    }

    public function testParseMultipleObjects() {
        $input = '{
            "@context": "https://schema.org",
            "@type": "Recipe",
            "name": "Baked bananas",
            "author": {
                "@type": "Person",
                "name": "Santa Claus"
            },
            "review": {
                "@type": "Review",
                "reviewBody": "Delicious!",
                "author": {
                    "@type": "Person",
                    "name": "Mrs. Claus"
                }
            }
        }';

        $expectedOutput = [
            1 => [
                "@context" => "https://schema.org",
                "@type" => "Recipe",
                "name" => "Baked bananas",
                "author" => [
                    "@id" => 2
                ],
                "review" => [
                    "@id" => 3
                ],
                "@id" => 1
            ],
            2 => [
                "@type" => "Person",
                "name" => "Santa Claus",
                "@id" => 2
            ],
            3 => [
                "@type" => "Review",
                "reviewBody" => "Delicious!",
                "author" => [
                    "@id" => 4
                ],
                "@id" => 3
            ],
            4 => [
                "@type" => "Person",
                "name" => "Mrs. Claus",
                "@id" => 4
            ]
        ];

        $parser = new FlattenJsonParser();
        $output = $parser->parse($input);
        $this->assertEquals($expectedOutput, $output);
    }
}
