<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CheckoutService;

class TestController extends Controller
{
    public function index() {

        $products = Product::all();

        $total = (new CheckoutService(config('pricing_rules')))->getTotal();

        return view('test.products', compact('products', 'total'));

    }

    public function scan($product_code) {
        $co = new CheckoutService(config('pricing_rules'));
        $co->scan($product_code);

        return redirect(route('test-products'));
    }
}
