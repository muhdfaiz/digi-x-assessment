<?php

require_once('Product.php');

class QuantityFreeUnitRule
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
     * @param int $freeUnit
     */
    public function apply(string $sku, int $minQuantity, $freeUnit)
    {
        $rule = [
            'sku' => $sku,
            'minimum_quantity' => $minQuantity,
            'type' => 'quantity_free_unit',
            'value' => $freeUnit
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
