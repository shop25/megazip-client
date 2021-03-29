<?php

namespace S25\MegazipApiClient\PHPUnit;

use S25\MegazipApiClient\Client;

class ApiTest extends \PHPUnit\Framework\TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client($_ENV['API_ENDPOINT']);
    }

    public function testFetchBrands()
    {
        $brands = $this->client->fetchBrands();

        $this->assertTrue(is_array($brands));
    }
}
