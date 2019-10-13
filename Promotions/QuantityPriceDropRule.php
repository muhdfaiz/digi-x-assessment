<?php

require_once('Product.php');

class QuantityPriceDropRule
{
    /**
     * List of free gift.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Product repository.
     *
     * @var Product
     */
    protected $product;

    /**
     * BulkDiscount constructor.
     */
    public function __construct()
    {
        $this->product = new Product();
    }

    /**
     * Apply free gift promotion rule.
     *
     * @param string $sku
     * @param int $minQuantity
     * @param float $pricePerItem
     */
    public function apply(string $sku, int $minQuantity, $pricePerItem)
    {
        $rule = [
            'sku' => $sku,
            'minimum_quantity' => $minQuantity,
            'type' => 'quantity_price_drop',
            'value' => $pricePerItem
        ];

        $this->rules[] = $rule;
    }

    /**
     * Get all free gift promotions.
     *
     * @return array
     */
    public function get()
    {
        return $this->rules;
    }
}
