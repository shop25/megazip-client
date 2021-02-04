<?php

namespace S25\MegazipApiClient;

class Utils
{
    public static function rawNumber(string $number): string
    {
        return strtoupper(preg_replace('/[^0-9a-z]+/ui', '', $number));
    }
}
