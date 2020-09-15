<?php

namespace S25\MegazipApiClient\Entities;

class Product
{
    public string $rawNumber;
    public string $number;
    public array $name = [];
    public float $weight;

    public static function fromResponse($data): ?self
    {
        $rawNumber = $data['number'] ?? '';
        $number = $data['number_formatted'] ?? '';
        $nameEn = $data['name'] ?? '';
        $weight = (float)$data['weight'];

        if (!$rawNumber || !$nameEn) {
            return null;
        }

        $product = new self();

        $product->rawNumber = self::rawNumber($rawNumber);
        $product->number = $number;
        $product->name['en'] = $nameEn;
        $product->weight = $weight;

        return $product;
    }

    private static function rawNumber(string $number): string
    {
        return strtoupper(preg_replace('/[^0-9a-z]+/ui', '', $number));
    }
}
