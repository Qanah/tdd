<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;

class CheckoutService
{

    private array $pricing_rules = [];
    public float $total = 0;

    public function __construct(array $pricing_rules)
    {
        $this->pricing_rules = $pricing_rules;
    }

    public function scan(string $product_code) {
        $product = Product::where('code', $product_code)->first();

        if($product) {
            Cart::create([
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        $this->total = $this->getTotal();
    }

    public function getTotal() {

        $carts = Cart::query()
            ->join('products', 'products.id', '=', 'carts.product_id')
            ->groupByRaw('products.code, products.price')
            ->selectRaw('products.code, products.price, sum(carts.quantity) quantity')
            ->get();

        $total = 0;

        foreach ($carts as $cart) {
            $rule = $this->pricing_rules[$cart['code']] ?? null;

            if($rule) {

                $method = $rule[0] ?? null; unset($rule[0]);

                if($method and method_exists($this, $method)) {
                    $total += $this->{$method}($cart, ...$rule);
                } else {
                    $total += $cart['price'] * $cart['quantity'];
                }
            } else {
                $total += $cart['price'] * $cart['quantity'];
            }
        }

        return $total;
    }

    private function buy_one_get_one_free($cart) {
        $quantity = floor($cart['quantity'] / 2) + $cart['quantity'] % 2;
        return $cart['price'] * $quantity;
    }

    private function bulk_price_discount($cart, $bulk_quantity, $discount_price) {

        $price = $cart['price'];

        if($cart['quantity'] >= $bulk_quantity) {
            $price = $discount_price;
        }

        return $price * $cart['quantity'];
    }

}
