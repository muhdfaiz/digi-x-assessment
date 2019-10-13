<?php

require_once(__DIR__ . '/../Helper.php');

class Promotion
{
    /**
     * List of promotions available.
     *
     * @var array
     */
    protected $promotions = [];

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * Promotion constructor.
     */
    public function __construct()
    {
        $this->helper = new Helper();
    }

    /**
     * Push promo.
     *
     * @param $promotion
     *
     * @return array
     */
    public function pushPromo($promotion)
    {
        $this->promotions = array_merge($this->promotions, $promotion->get());

        return $this->promotions;
    }

    public function applyPromo(array $scannedItems, float $totalAmount)
    {
        $totalDiscountAmount = 0;

        foreach ($this->promotions as $key => $promotion) {
            $totalDiscountAmount += $this->applyQuantityFreeunitPromo($scannedItems, $promotion);

            $totalDiscountAmount += $this->applyQuantityPriceDropPromo($scannedItems, $promotion);

            $totalDiscountAmount += $this->applyFreeGifts($scannedItems, $promotion);
        }

        return $totalAmount - $totalDiscountAmount;
    }

    /**
     * Apply quantity free unit rule.
     * For example, if you bought item A more than 3 units, you only pay 2 units.
     *
     * @param array $scannedItems
     * @param array $promotion
     *
     * @return float|int|void
     * @throws Exception
     */
    protected function applyQuantityFreeunitPromo(array $scannedItems, array $promotion)
    {
        // Skip if promotion type not equal to 'quantity_free_unit'
        if ($promotion['type'] !== 'quantity_free_unit') {
            return;
        }

        // Search scanned items contains promotion item.
        $item = $this->helper->searchArray($scannedItems, 'sku', $promotion['sku']);

        // Skip if scanned items does not contain promotion item.
        if (!$item) {
            return;
        }

        // Skip if scanned item quantity below minimum quantity rule for the promotion.
        if ($item['quantity'] < $promotion['minimum_quantity']) {
            return;
        }

        // Calculate discount amount
        $discountAmount = (int) $promotion['value'] * (float) $item['price'];

        return $discountAmount;
    }

    /**
     * Apply quantity price drop rules.
     * For example, if you bought item A with price $80.00 more than 5 unit then the price per unit for item A become $50.00.
     *
     * @param array $scannedItems
     * @param array $promotion
     *
     * @return float|int|void
     * @throws Exception
     */
    protected function applyQuantityPriceDropPromo(array $scannedItems, array $promotion)
    {
        // Skip if promotion type not equal to 'quantity_price_drop'
        if ($promotion['type'] !== 'quantity_price_drop') {
            return 0;
        }

        // Search scanned items contains promotion item.
        $item = $this->helper->searchArray($scannedItems, 'sku', $promotion['sku']);

        // Skip if scanned items does not contain promotion item.
        if (!$item) {
            return 0;
        }

        // Skip if scanned item quantity below minimum quantity rule for the promotion.
        if ($item['quantity'] < $promotion['minimum_quantity']) {
            return 0;
        }

        // Calculate discount amount
        $discountAmount = ((float) $item['price'] - (float) $promotion['value']) * (int) $item['quantity'];

        return $discountAmount;
    }

    /**
     * Apply free gift rule.
     * For example, if you bought item A you will get item B as a free.
     *
     * @param array $scannedItems
     * @param array $promotion
     *
     * @return float|int|void
     * @throws Exception
     */
    protected function applyFreeGifts(array $scannedItems, array $promotion)
    {
        // Skip if promotion type not equal to 'free_gift'
        if ($promotion['type'] !== 'free_gift') {
            return 0;
        }

        // Search scanned items contains promotion item.
        $item = $this->helper->searchArray($scannedItems, 'sku', $promotion['sku']);

        // Skip if scanned items does not contain promotion item.
        if (!$item) {
            return 0;
        }

        // Search scanned items contains free gift item.
        $freeGiftItem = $this->helper->searchArray($scannedItems, 'sku', $promotion['value']);


        // Skip if scanned item does not contain free gift item.
        if (!$freeGiftItem) {
            return 0;
        }

        // Skip if scanned item quantity does not contain free gift item.
        if ($item['quantity'] < $promotion['minimum_quantity']) {
            return 0;
        }

        // Calculate discount amount
        $discountAmount = $freeGiftItem['price'];

        return $discountAmount;
    }
}
