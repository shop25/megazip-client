<?php

namespace S25\MegazipApiClient\Resources;

use S25\MegazipApiClient\Entities\Product as ProductEntity;

class Product implements \JsonSerializable
{
    private ProductEntity $entity;

    private function __construct(ProductEntity $entity)
    {
        $this->entity = $entity;
    }

    /** @inheritDoc */
    public function jsonSerialize()
    {
        return [
            'name' => $this->entity->name,
            'number' => $this->entity->number,
            'weight' => $this->entity->weight,
        ];
    }

    public static function fromEntity(ProductEntity $product): self
    {
        return new self($product);
    }

    public static function fromArray(array $products): array
    {
        return array_map([self::class, 'fromEntity'], $products);
    }
}