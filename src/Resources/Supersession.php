<?php

namespace S25\MegazipApiClient\Resources;

use S25\MegazipApiClient\Entities\SupersedingPart;
use S25\MegazipApiClient\Entities\Supersession as SupersessionEntity;

class Supersession implements \JsonSerializable
{
    private SupersessionEntity $entity;

    public function __construct(SupersessionEntity $entity)
    {
        $this->entity = $entity;
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => 'set',
            'old_oem' => $this->entity->oldRawNumber,
            'new_oems' => array_map(
                fn(SupersedingPart $part) => ['oem' => $part->newRawNumber, 'qty' => $part->quantity],
                $this->entity->newParts
            ),
        ];
    }

    public static function fromEntity(SupersessionEntity $entity): self
    {
        return new self($entity);
    }

    public static function fromArray(array $entities): array
    {
        return array_map([self::class, 'fromEntity'], $entities);
    }
}
