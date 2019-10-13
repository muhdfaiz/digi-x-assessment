<?php

require_once('Product.php');
require_once('Helper.php');

class Checkout
{
    /**
     * Product Repository.
     *
     * @var Product $product
     */
    protected $product;

    /**
     * List of checkout items.
     *
     * @var array
     */
    protected $items = [];

    /**
     * @var Promotion
     */
    protected $promotion;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * Checkout constructor.
     */
    public function __construct(Promotion $promotion)
    {
        $this->product = new Product();
        $this->promotion = $promotion;
        $this->helper = new Helper();
    }

    /**
     * Scan item.
     * Throw an exception when product not exist.
     *
     * @param string $sku
     *
     * @return array
     * @throws Exception
     */
    public function scan(string $sku)
    {
        // Retrieve product information by SKU.
        $item = $this->product->get('sku', $sku);

        // Check if item has been scan.
        // If already scan increase the quantity and total price.
        // Otherwise add the item.
        if ($matchedItem = $this->helper->searchArray($this->items, 'sku', $item['sku'])) {
            $this->items[$item['sku']]['quantity'] += 1;

            $totalPrice = $this->helper->formatPrice($matchedItem['price'] * $this->items[$item['sku']]['quantity']);
            $this->items[$item['sku']]['total_price'] = $totalPrice;
        } else {
            $this->items[$item['sku']] = [
                'sku' => $item['sku'],
                'name' => $item['name'],
                'quantity' => 1,
                'price' => $item['price'],
                'total_price' => $item['price']
            ];
        }

        return $this->items;
    }

    /**
     * Calculate total amount of scanned items after promotion applied .
     *
     * @return int|mixed
     */
    public function total()
    {
        $totalAmount = 0;

        foreach ($this->items as $item) {
            $totalAmount += $item['total_price'];
        }

        $totalAmount = $this->promotion->applyPromo($this->items, $totalAmount);

        return $this->helper->formatPrice($totalAmount);
    }
}
