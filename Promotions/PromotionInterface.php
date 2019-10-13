<?php

interface PromotionInterface
{
    /**
     * Apply promo.
     *
     * @param string $sku
     * @param int $minQuantity
     * @param $promo
     *
     * @return mixed
     */
    public function apply(string $sku, int $minQuantity, $promo);

    /**
     * Get all promo
     *
     * @return mixed
     */
    public function get();
}