<?php

namespace S25\MegazipApiClient\Entities;

use S25\MegazipApiClient\Utils;

final class Supersession
{
    public string $oldRawNumber;
    /** @var SupersedingPart[] */
    public array $newParts;

    public function __construct(string $oldRawNumber, ?string $newRawNumber = null)
    {
        $this->oldRawNumber = Utils::rawNumber($oldRawNumber);
        if ($newRawNumber !== null) {
            $this->addNewPart($newRawNumber);
        }
    }

    public function addNewPart(string $newRawNumber, int $quantity = 1)
    {
        $this->newParts[] = new SupersedingPart($newRawNumber, $quantity);
    }
}
