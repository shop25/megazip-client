<?php

namespace S25\MegazipApiClient\Entities;

class Brand
{
    public string $slug;
    public string $name;
    public string $logo;

    public function __construct(string $slug, string $name, string $logo = '')
    {
        $this->slug = $slug;
        $this->name = $name;
        $this->logo = $logo;
    }

    public static function fromResponse($data): ?self
    {
        $slug = $data['slug'] ?? null;
        $name = $data['name'] ?? null;
        $logo = $data['image'] ?? '';

        if (!$slug || !is_string($slug) || !$name || !is_string($name)) {
            return null;
        }

        return new self($slug, $name, is_string($logo) ? $logo : '');
    }
}
