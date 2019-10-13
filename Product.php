<?php

require_once('Helper.php');

class Product
{
    /**
     * Product collection
     *
     * @var array
     */
    protected $products = [];

    protected $helper;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->products = json_decode(file_get_contents('product.json'), true);
        $this->helper = new Helper();
    }

    /**
     * Get product by field and value.
     *
     * @param string $field
     * @param string $value
     *
     * @return array
     * @throws Exception
     */
    public function get(string $field, string $value)
    {
        $product = $this->helper->searchArray($this->products, $field, $value);

        if (!$product) {
            throw new Exception("Product not exist in our store.");
        }

        return $product;
    }
}