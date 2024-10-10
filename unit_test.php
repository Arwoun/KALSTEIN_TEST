<?php

use PHPUnit\Framework\TestCase;

class ProductApiTest extends TestCase
{
    private $baseUrl = 'http://localhost:5000/api/produits';

    public function testGetAllProducts()
    {
        $response = $this->makeRequest('GET', $this->baseUrl);
        $this->assertEquals(200, $response['status']);
        $this->assertIsArray($response['body']);
    }

    public function testGetProductById()
    {
        $response = $this->makeRequest('GET', $this->baseUrl . '/1');
        $this->assertEquals(200, $response['status']);
        $this->assertArrayHasKey('product_aid', $response['body']);
    }

    public function testAddProduct()
    {
        $newProduct = [
            'product_name_fr' => 'Test Product',
            'product_peso_bruto' => 10.5,
            'product_stock_units' => 100
        ];

        $response = $this->makeRequest('POST', $this->baseUrl, $newProduct);
        $this->assertEquals(201, $response['status']);
        $this->assertArrayHasKey('message', $response['body']);
    }

    public function testUpdateProduct()
    {
        $updatedProduct = [
            'product_name_fr' => 'Updated Test Product',
            'product_peso_bruto' => 20.5,
            'product_stock_units' => 200
        ];

        $response = $this->makeRequest('PUT', $this->baseUrl . '/1', $updatedProduct);
        $this->assertEquals(200, $response['status']);
        $this->assertArrayHasKey('message', $response['body']);
    }

    public function testDeleteProduct()
    {
        $response = $this->makeRequest('DELETE', $this->baseUrl . '/1');
        $this->assertEquals(200, $response['status']);
        $this->assertArrayHasKey('message', $response['body']);
    }

    private function makeRequest($method, $url, $data = null)
    {
        $options = [
            'http' => [
                'header' => "Content-Type: application/json\r\n",
                'method' => $method,
            ]
        ];

        if ($data) {
            $options['http']['content'] = json_encode($data);
        }

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $status = $http_response_header[0];
        preg_match('{HTTP\/\S*\s(\d{3})}', $status, $match);
        $status_code = $match[1];

        return [
            'status' => (int)$status_code,
            'body' => json_decode($result, true)
        ];
    }
}
