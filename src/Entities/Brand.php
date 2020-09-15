<?php

namespace S25\MegazipApiClient\Entities;

class Brand
{
    public string $slug;
    public string $name;
    public string $logo;

    public static function fromResponse($data): ?self
    {
        $slug = $data['slug'] ?? null;
        $name = $data['name'] ?? null;
        $logo = $data['image'] ?? null;

        if (!$slug || !is_string($slug) || !$name || !is_string($name)) {
            return null;
        }

        $brand = new self();
        $brand->slug = $slug;
        $brand->name = $name;
        $brand->logo = is_string($logo) ? $logo : '';

        return $brand;
    }
}
