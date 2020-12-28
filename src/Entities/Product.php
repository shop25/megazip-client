<?php

namespace S25\MegazipApiClient\Entities;

class Product
{
    public string $rawNumber;
    public string $number;
    public array  $name = [];
    public float  $weight;

    public function __construct(string $rawNumber, string $number, array $name, float $weight)
    {
        $this->rawNumber = self::rawNumber($rawNumber);
        $this->number = $number;
        $this->name = $name;
        $this->weight = $weight;
    }

    public static function fromResponse($data): ?self
    {
        $rawNumber = $data['number'] ?? '';
        $number = $data['number_formatted'] ?? '';
        $nameEn = $data['name'] ?? '';
        $weight = (float)($data['weight'] ?? '');

        if (!$rawNumber || !$nameEn) {
            return null;
        }

        return new self($rawNumber, $number, ['en' => $nameEn], $weight);
    }

    public static function rawNumber(string $number): string
    {
        return strtoupper(preg_replace('/[^0-9a-z]+/ui', '', $number));
    }
}
