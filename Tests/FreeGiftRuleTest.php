<?php

require_once('Promotions/FreeGiftRule.php');

use PHPUnit\Framework\TestCase;

class FreeGiftRuleTest extends TestCase
{
    public function testApplyShouldAddFreeGiftRule()
    {
        $sku = 'mbp';
        $minQuantity = 1;
        $freeGift = 'vga';

        $rule = new FreeGiftRule();
        $rule->apply($sku, $minQuantity, $freeGift);

        $rules = $rule->get();

        $expected = [
            [
                'sku' => $sku,
                'minimum_quantity' => $minQuantity,
                'type' => 'free_gift',
                'value' => $freeGift
            ]
        ];

        $this->assertEquals($expected, $rules);
    }
}
