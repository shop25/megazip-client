<?php

namespace S25\MegazipApiClient\Entities;

use S25\MegazipApiClient\Utils;

final class SupersedingPart
{
    public string $newRawNumber;
    public int $quantity;

    public function __construct(string $newRawNumber, int $quantity)
    {
        $this->newRawNumber = Utils::rawNumber($newRawNumber);
        $this->quantity = $quantity;
    }
}
