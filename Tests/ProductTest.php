<?php

require_once('Product.php');

use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testGetShouldReturnTheProduct()
    {
        $product = new Product();

        $productDetails = $product->get('sku', 'atv');

        $expected = [
            'sku' => 'atv',
            'name' => 'Apple TV',
            'price' => '109.50'
        ];

        $this->assertEquals($expected, $productDetails);
    }

    public function testGetShouldReturnException()
    {
        $product = new Product();

        $this->expectException(Exception::class);

        $product->get('sku', 'atvs');
    }
}
