<?php

namespace S25\MegazipApiClient;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use S25\MegazipApiClient\Entities\Brand;
use S25\MegazipApiClient\Entities\Product;
use S25\MegazipApiClient\Resources\Product as ProductResource;

class Client
{
    private HttpClient $httpClient;

    public function __construct(
        string $endpoint,
        Options $options = null
    ) {
        if (!$endpoint) {
            throw new \RuntimeException('Invalid endpoint address ' . $endpoint);
        }

        $options ??= new Options();

        $stack = HandlerStack::create();

        if ($options->logger !== null) {
            $stack->push(
                Middleware::log(
                    $options->logger,
                    new MessageFormatter($options->getFormat())
                )
            );
        }

        $this->httpClient = new HttpClient(
            [
                'base_uri' => $endpoint,
                'handler' => $stack,
            ]
        );
    }

    /**
     * @return Brand[]
     * @throws GuzzleException
     */
    public function fetchBrands(): array
    {
        $response = $this->httpClient->get('get_manufacturers');

        $result = $this->validateResponse($response);

        $records = $this->validateArray($result['manufacturers'] ?? null, 'result.manufacturers');

        return array_filter(array_map([Brand::class, 'fromResponse'], $records));
    }

    /**
     * @param string $brandSlug
     * @return string[]
     * @throws GuzzleException
     */
    public function fetchFormats($brandSlug): array
    {
        $query = ['manufacturer' => $brandSlug];

        $response = $this->httpClient->get('number_formats', [RequestOptions::QUERY => $query]);

        $records = $this->validateResponse($response);

        return $this->validateArray($records);
    }

    public function fetchName(string $brandSlug, string $number): ?string
    {
        $query = ['manufacturer' => $brandSlug, 'number' => $number];

        $response = $this->httpClient->get('get_item', [RequestOptions::QUERY => $query]);

        $result = $this->validateResponse($response);

        return $result['item']['name'] ?? null;
    }

    /**
     * @param string $brandSlug
     * @param string[] $numbers
     * @return Product[]
     * @throws GuzzleException
     */
    public function fetchProducts(string $brandSlug, array $numbers): array
    {
        $params = ['manufacturer' => $brandSlug, 'numbers' => json_encode($numbers, JSON_THROW_ON_ERROR)];

        $response = $this->httpClient->post('get_items_bulk', [RequestOptions::FORM_PARAMS => $params]);

        $result = $this->validateResponse($response);

        $records = $this->validateArray($result['items'] ?? null, 'result.items');

        return array_filter(array_map([Product::class, 'fromResponse'], $records));
    }

    /**
     * @param string $brandSlug
     * @param Product[] $products
     * @return void
     * @throws GuzzleException
     */
    public function bombWithProducts(string $brandSlug, array $products): void
    {
        $productResources = ProductResource::fromArray($products);

        $query = ['manufacturer' => $brandSlug, 'data' => json_encode($productResources, JSON_THROW_ON_ERROR)];

        $response = $this->httpClient->get('item_bombing', [RequestOptions::QUERY => $query]);

        $result = $this->validateArray($this->validateResponse($response));

        $status = $result['stat'] ?? null;

        if ($status !== 'ok') {
            throw new \RuntimeException('Result status is not "ok"');
        }
    }

    private function validateResponse(ResponseInterface $response)
    {
        $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($data)) {
            throw new \RuntimeException('Response data is not array');
        }

        if (!array_key_exists('result', $data)) {
            throw new \RuntimeException('No result is returned');
        }

        return $data['result'];
    }

    private function validateArray($result, $path = 'result')
    {
        if (!is_array($result)) {
            throw new \RuntimeException($path . ' must be an array');
        }

        return $result;
    }
}
