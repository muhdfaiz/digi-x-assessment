<?php

require_once('Checkout.php');
require_once('Promotions/Promotion.php');
require_once('Product.php');
require_once('Promotions/QuantityFreeUnitRule.php');
require_once('Promotions/QuantityPriceDropRule.php');
require_once('Promotions/FreeGiftRule.php');

use PHPUnit\Framework\TestCase;

class CheckoutTest extends TestCase
{
    public function testScanShouldAddOneItemWithOneQuantity()
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

        $stub = $this->createMock(Product::class);
        $stub->method('get')->willReturn($products);

        $promotion = new Promotion();
        $checkout = new Checkout($promotion);

        $items = $checkout->scan('mbp');


        $expected = [
            'mbp' => [
                'sku' => $products[1]['sku'],
                'name' => $products[1]['name'],
                'total_price' => $products[1]['price'],
                'price' => $products[1]['price'],
                'quantity' => 1
            ]
        ];

        $this->assertEquals($expected, $items);
    }

    public function testScanShouldAddOneItemWithMultipleQuantity()
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

        $stub = $this->createMock(Product::class);
        $stub->method('get')->willReturn($products);

        $promotion = new Promotion();
        $checkout = new Checkout($promotion);

        $checkout->scan('mbp');
        $checkout->scan('mbp');
        $items = $checkout->scan('mbp');


        $expected = [
            'mbp' => [
                'sku' => $products[1]['sku'],
                'name' => $products[1]['name'],
                'total_price' => $products[1]['price'] * 3,
                'price' => $products[1]['price'],
                'quantity' => 3
            ]
        ];

        $this->assertEquals($expected, $items);
    }

    public function testScanShouldAddMultipleItemWithMultipleQuantity()
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

        $stub = $this->createMock(Product::class);
        $stub->method('get')->willReturn($products);

        $promotion = new Promotion();
        $checkout = new Checkout($promotion);

        $checkout->scan('mbp');
        $checkout->scan('ipd');
        $checkout->scan('mbp');
        $checkout->scan('ipd');
        $items = $checkout->scan('mbp');


        $expected = [
            $products[1]['sku'] => [
                'sku' => $products[1]['sku'],
                'name' => $products[1]['name'],
                'total_price' => $products[1]['price'] * 3,
                'price' => $products[1]['price'],
                'quantity' => 3
            ],
            $products[0]['sku'] => [
                'sku' => $products[0]['sku'],
                'name' => $products[0]['name'],
                'total_price' => $products[0]['price'] * 2,
                'price' => $products[0]['price'],
                'quantity' => 2
            ]

        ];

        $this->assertEquals($expected, $items);
    }

    public function testTotalWithOneItemShouldReturnCorrectAmount()
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

        $stub = $this->createMock(Product::class);
        $stub->method('get')->willReturn($products);

        $promotion = new Promotion();
        $checkout = new Checkout($promotion);

        $checkout->scan('ipd');

        $totalAmount = $checkout->total();

        $expected = $products[0]['price'];

        $this->assertEquals($expected, $totalAmount);
    }

    public function testTotalWithOneItemMultipleQuantityShouldReturnCorrectAmount()
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

        $stub = $this->createMock(Product::class);
        $stub->method('get')->willReturn($products);

        $promotion = new Promotion();
        $checkout = new Checkout($promotion);

        $checkout->scan('ipd');
        $checkout->scan('ipd');

        $totalAmount = $checkout->total();

        $expected = $products[0]['price'] * 2;

        $this->assertEquals($expected, $totalAmount);
    }

    public function testTotalWithMultipleItemWithMultipleQuantityShouldReturnCorrectAmount()
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

        $stub = $this->createMock(Product::class);
        $stub->method('get')->willReturn($products);

        $promotion = new Promotion();
        $checkout = new Checkout($promotion);

        $checkout->scan('mbp');
        $checkout->scan('ipd');
        $checkout->scan('mbp');
        $checkout->scan('ipd');
        $checkout->scan('ipd');

        $totalAmount = $checkout->total();

        $expected = ($products[0]['price'] * 3) + ($products[1]['price'] * 2);

        $this->assertEquals($expected, $totalAmount);
    }

    public function testTotalWithPromotionQuantityFreeUnit()
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
            [
                'sku' => 'atv',
                'name' => 'Apple TV',
                'price' => '109.50'
            ],
            [
                'sku' => 'vga',
                'name' => 'VGA adapter',
                'price' => '30.00'
            ],
        ];

        $stub = $this->createMock(Product::class);
        $stub->method('get')->willReturn($products);

        $freeUnit = 1;
        $rule = new QuantityFreeUnitRule();
        $rule->apply('atv', 3, $freeUnit);

        $promotion = new Promotion();
        $promotion->pushPromo($rule);

        $checkout = new Checkout($promotion);

        $checkout->scan('atv');
        $checkout->scan('atv');
        $checkout->scan('atv');
        $checkout->scan('vga');

        $totalAmount = $checkout->total();

        $expected = ($products[3]['price']) + ($products[2]['price'] * 2);

        $this->assertEquals($expected, $totalAmount);
    }

    public function testTotalWithPromotionQuantityPriceDrop()
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
            [
                'sku' => 'atv',
                'name' => 'Apple TV',
                'price' => '109.50'
            ],
            [
                'sku' => 'vga',
                'name' => 'VGA adapter',
                'price' => '30.00'
            ],
        ];

        $stub = $this->createMock(Product::class);
        $stub->method('get')->willReturn($products);

        $promoPrice = 499.99;
        $rule = new QuantityPriceDropRule();
        $rule->apply('ipd', 5, $promoPrice);

        $promotion = new Promotion();
        $promotion->pushPromo($rule);

        $checkout = new Checkout($promotion);

        $checkout->scan('atv');
        $checkout->scan('ipd');
        $checkout->scan('ipd');
        $checkout->scan('atv');
        $checkout->scan('ipd');
        $checkout->scan('ipd');
        $checkout->scan('ipd');

        $totalAmount = $checkout->total();

        $expected = ($promoPrice * 5) + ($products[2]['price'] * 2);

        $this->assertEquals($expected, $totalAmount);
    }

    public function testTotalWithPromotionFreeGift()
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
            [
                'sku' => 'atv',
                'name' => 'Apple TV',
                'price' => '109.50'
            ],
            [
                'sku' => 'vga',
                'name' => 'VGA adapter',
                'price' => '30.00'
            ],
        ];

        $stub = $this->createMock(Product::class);
        $stub->method('get')->willReturn($products);

        $rule = new FreeGiftRule();
        $rule->apply('mbp', 1, 'vga');

        $promotion = new Promotion();
        $promotion->pushPromo($rule);

        $checkout = new Checkout($promotion);

        $checkout->scan('mbp');
        $checkout->scan('vga');
        $checkout->scan('ipd');

        $totalAmount = $checkout->total();

        $expected = $products[0]['price'] + $products[1]['price'];

        $this->assertEquals($expected, $totalAmount);
    }

    public function testTotalWithMultiplePromotion()
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
            [
                'sku' => 'atv',
                'name' => 'Apple TV',
                'price' => '109.50'
            ],
            [
                'sku' => 'vga',
                'name' => 'VGA adapter',
                'price' => '30.00'
            ],
        ];

        $stub = $this->createMock(Product::class);
        $stub->method('get')->willReturn($products);

        $freeUnit = 1;
        $rule1 = new QuantityFreeUnitRule();
        $rule1->apply('atv', 3, $freeUnit);

        $promoPrice = 499.99;
        $rule2 = new QuantityPriceDropRule();
        $rule2->apply('ipd', 5, $promoPrice);

        $rule3 = new FreeGiftRule();
        $rule3->apply('mbp', 1, 'vga');

        $promotion = new Promotion();
        $promotion->pushPromo($rule1);
        $promotion->pushPromo($rule2);
        $promotion->pushPromo($rule3);

        $checkout = new Checkout($promotion);

        $checkout->scan('mbp');
        $checkout->scan('mbp');
        $checkout->scan('vga');
        $checkout->scan('ipd');
        $checkout->scan('atv');
        $checkout->scan('atv');
        $checkout->scan('atv');
        $checkout->scan('ipd');
        $checkout->scan('ipd');
        $checkout->scan('ipd');
        $checkout->scan('ipd');

        $totalAmount = $checkout->total();

        $expected = ($products[1]['price'] * 2) + ($promoPrice * 5) + $products[2]['price'] * 2;

        $this->assertEquals($expected, $totalAmount);
    }
}
