<?php

namespace S25\MegazipApiClient\Entities;

use S25\MegazipApiClient\Utils;

final class Weight
{
    public string $rawNumber;
    public float $value;

    public function __construct(string $rawNumber, float $weight)
    {
        $this->rawNumber = Utils::rawNumber($rawNumber);
        $this->value = $weight;
    }
}
