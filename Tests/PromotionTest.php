<?php

require_once('Promotions/Promotion.php');

use PHPUnit\Framework\TestCase;

class PromotionTest extends TestCase
{
    public function testPushSinglePromoShouldAddPromo()
    {
        $sku = 'mbp';
        $minQuantity = 1;
        $freeUnit = 1;

        $rule = new QuantityFreeUnitRule();
        $rule->apply($sku, $minQuantity, $freeUnit);

        $promotion = new Promotion();
        $promotions = $promotion->pushPromo($rule);

        $expected = [
            [
                'sku' => $sku,
                'minimum_quantity' => $minQuantity,
                'type' => 'quantity_free_unit',
                'value' => $freeUnit
            ]
        ];

        $this->assertEquals($expected, $promotions);
    }

    public function testPushMultiplePromoShouldAddPromo()
    {
        $sku = 'mbp';
        $minQuantity = 1;
        $freeUnit = 1;

        $rule1 = new QuantityFreeUnitRule();
        $rule1->apply($sku, $minQuantity, $freeUnit);

        $skuFreeGift = 'atv';
        $minQuantityFreeGift = 2;
        $freeGift = 1;

        $rule2 = new FreeGiftRule();
        $rule2->apply($skuFreeGift, $minQuantityFreeGift, $freeGift);

        $promotion = new Promotion();
        $promotions = $promotion->pushPromo($rule1);
        $promotions = $promotion->pushPromo($rule2);

        $expected = [
            [
                'sku' => $sku,
                'minimum_quantity' => $minQuantity,
                'type' => 'quantity_free_unit',
                'value' => $freeUnit
            ],
            [
                'sku' => $skuFreeGift,
                'minimum_quantity' => $minQuantityFreeGift,
                'type' => 'free_gift',
                'value' => $freeGift
            ]
        ];

        $this->assertEquals($expected, $promotions);
    }
}