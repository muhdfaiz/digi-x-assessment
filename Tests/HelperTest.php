<?php

require_once('Helper.php');

use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    public function testSearchArrayShouldReturnItem()
    {
        $products = [
            [
                'sku' => 'ipd',
                'name' => 'Super iPad',
                'price' => '549.99'
            ],
            [
                'sku' => 'mbp',
                'name' => 'MacBook Pro',
                'price' => '1399.99'
            ],
        ];

        $helper = new Helper();

        $item = $helper->searchArray($products, 'sku', 'mbp');

        $this->assertEquals($products[1], $item);
    }

    public function testSearchArrayShouldReturnNull()
    {
        $products = [
            [
                'sku' => 'ipd',
                'name' => 'Super iPad',
                'price' => '549.99'
            ],
            [
                'sku' => 'mbp',
                'name' => 'MacBook Pro',
                'price' => '1399.99'
            ],
        ];

        $helper = new Helper();

        $item = $helper->searchArray($products, 'sku', 'aptv');

        $this->assertNull($item);
    }

    public function testFormatPrice()
    {
        $helper = new Helper();

        $price = $helper->formatPrice(200.9999);

        $this->assertEquals(200.99, $price);
    }
}
