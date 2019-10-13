<?php

require_once('PromotionInterface.php');

class FreeGiftRule implements PromotionInterface
{
    /**
     * List of free gift.
     *
     * @var array
     */
    protected $freeGifts = [];

    /**
     * Apply free gift promotion rule.
     *
     * @param string $sku
     * @param int $minQuantity
     * @param $freeGift
     */
    public function apply(string $sku, int $minQuantity, $freeGift)
    {
        $freeGift = [
            'sku' => $sku,
            'minimum_quantity' => $minQuantity,
            'type' => 'free_gift',
            'value' => $freeGift
        ];

        $this->freeGifts[] = $freeGift;
    }

    /**
     * Get all free gift promotions.
     *
     * @return array
     */
    public function get()
    {
        return $this->freeGifts;
    }
}
