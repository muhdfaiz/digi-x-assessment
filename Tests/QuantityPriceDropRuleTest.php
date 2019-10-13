<?php

require_once('Promotions/QuantityPriceDropRule.php');

use PHPUnit\Framework\TestCase;

class QuantityPriceDropRuleTest extends TestCase
{
    public function testApplyShouldAddTheRule()
    {
        $sku = 'mbp';
        $minQuantity = 1;
        $pricePerItem = '122.55';

        $rule = new QuantityPriceDropRule();
        $rule->apply($sku, $minQuantity, $pricePerItem);

        $rules = $rule->get();

        $expected = [
            [
                'sku' => $sku,
                'minimum_quantity' => $minQuantity,
                'type' => 'quantity_price_drop',
                'value' => $pricePerItem
            ]
        ];

        $this->assertEquals($expected, $rules);
    }
}
