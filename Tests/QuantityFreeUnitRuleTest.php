<?php

require_once('Promotions/QuantityPriceDropRule.php');

use PHPUnit\Framework\TestCase;

class QuantityFreeUnitRuleTest extends TestCase
{
    public function testApplyShouldAddTheRule()
    {
        $sku = 'mbp';
        $minQuantity = 1;
        $freeUnit = 1;

        $rule = new QuantityFreeUnitRule();
        $rule->apply($sku, $minQuantity, $freeUnit);

        $rules = $rule->get();

        $expected = [
            [
                'sku' => $sku,
                'minimum_quantity' => $minQuantity,
                'type' => 'quantity_free_unit',
                'value' => $freeUnit
            ]
        ];

        $this->assertEquals($expected, $rules);
    }
}
