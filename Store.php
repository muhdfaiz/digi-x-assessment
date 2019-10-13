<?php

require_once('Checkout.php');
require_once('Product.php');
require_once('Promotions/Promotion.php');
require_once('Promotions/QuantityFreeUnitRule.php');
require_once('Promotions/QuantityPriceDropRule.php');
require_once('Promotions/FreeGiftRule.php');

/***************************************************************
 * Situation:                                                  *
 * SKUs Scanned: atv, atv, atv, vga                            *
 * Total expected: $249.00                                     *
 **************************************************************/

// Apply quantity free unit rule.
$rule = new QuantityFreeUnitRule();
$rule->apply('atv', 3, 1);

// Push all promotions.
$promotion = new Promotion();
$promotion->pushPromo($rule);

$checkout = new Checkout($promotion);

$checkout->scan('atv');
$checkout->scan('atv');
$checkout->scan('atv');
$checkout->scan('vga');

$totalAmount = $checkout->total();

echo "SKUs Scanned: atv, atv, atv, vga\r\n";
echo "Total expected: $249.00\r\n";
echo "Total Amount: $" . $totalAmount . "\r\n\r\n";



/***************************************************************
 * Situation:                                                  *
 * SKUs Scanned: atv, ipd, ipd, atv, ipd, ipd, ipd             *
 * Total expected: $2718.95                                    *
 **************************************************************/

// Apply quantity price drop rule.
$rule = new QuantityPriceDropRule();
$rule->apply('ipd', 5, 499.99);

// Push all promotions.
$promotion = new Promotion();
$promotion->pushPromo($rule);

$checkout = new Checkout($promotion);

$checkout->scan('atv');
$checkout->scan('ipd');
$checkout->scan('ipd');
$checkout->scan('atv');
$checkout->scan('ipd');
$checkout->scan('ipd');
$checkout->scan('ipd');

$totalAmount = $checkout->total();

echo "SKUs Scanned: atv, atv, atv, vga\r\n";
echo "Total expected: $2718.95\r\n";
echo "Total Amount: $" . $totalAmount . "\r\n\r\n";



/***************************************************************
 * Situation:                                                  *
 * SKUs Scanned: mbp, vga, ipd                                 *
 * Total expected: $1949.98                                    *
 **************************************************************/

// Apply free gift rule.
$rule = new FreeGiftRule();
$rule->apply('mbp', 1, 'vga');

// Push all promotions.
$promotion = new Promotion();
$promotion->pushPromo($rule);

$checkout = new Checkout($promotion);

$checkout->scan('mbp');
$checkout->scan('vga');
$checkout->scan('ipd');

$totalAmount = $checkout->total();

echo "SKUs Scanned: atv, atv, atv, vga\r\n";
echo "Total expected: $1949.98\r\n";
echo "Total Amount: $" . $totalAmount . "\r\n\r\n";