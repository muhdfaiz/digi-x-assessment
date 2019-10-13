<?php

class Helper
{
    /**
     * Search array by specific key
     *
     * @param array $collection
     * @param $field
     * @param $value
     *
     * @return array
     * @throws Exception
     */
    public function searchArray(array $collection, string $field, string $value)
    {
        foreach ($collection as $key => $item) {
            if (isset($item[$field]) && $item[$field] === $value) {
                return $item;
            }
        }

        return null;
    }

    public function formatPrice($amount)
    {
        return floor(($amount*100))/100;
    }
}
