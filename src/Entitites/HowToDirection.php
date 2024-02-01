<?php

namespace Entities;

class HowToDirection
{
    /**
     * @var array<string> Textual content of the direction.
     */
    private array $Text;

    /**
     * @var array<HowToSupply> List of supplies for the direction.
     */
    private array $Supply;
}