<?php

/**
 * Notes: Please use about an hour for this test.
 * You can use a simple class, a framework, or structured files as you see fit
 *
 * To return the test, either send a zipped archive of code or a *private* repo link.
 *
 * ----------------------------------------------------------------------------------
 *
 * Our supermarket's quest for global reach has prompted us to open a new supermarket - we sell only
 * three products:
 *
 * Product code | Name          | Price
 * ---------------------------------------
 * FR1          | Fruit tea     | $3.11
 * SR1          | Strawberries  | $5.00
 * CF1          | Coffee        | $11.23
 *
 * The CEO is a big fan of buy-one-get-one-free offers and of fruit tea. He wants us add a rule to do this.
 *
 * The COO, though, likes low prices and wants people buying strawberries to get a price discount for bulk purchases.
 * If you buy 3 or more strawberries, the price should drop to $4.50
 *
 * Our check-out can scan items in any order, and because the CEO and COO change their minds often,
 * it needs to be flexible regarding our pricing rules.
 *
 * The interface to our checkout looks this (shown in PHP)
 *
 * $co = new Checkout($pricing_rules);
 * $co->scan($item);
 * $co->scan($item);
 * $price = $co->total;
 *
 * Implement a checkout system that fulfils these requirements.
 *
 * Test data
 * ---------
 * Basket: FR1,SR1,FR1,FR1,CF1
 * Total price: 22.45
 *
 * Basket: FR1,FR1
 * Total price: 3.11
 *
 * Basket: SR1,SR1,FR1,SR1
 * Total price: 16.61
 */

namespace Tests\Unit;

use App\Services\CheckoutService;
use Database\Seeders\ProductsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private array $pricing_rules = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ProductsSeeder::class);

        $this->pricing_rules = config('pricing_rules', []);
    }

    /**
     * Basket: FR1,SR1,FR1,FR1,CF1
     * Total price: 22.45
     *
     * @return void
     */
    public function test_checkout1()
    {

        $co = new CheckoutService($this->pricing_rules);
        $co->scan('FR1');
        $co->scan('SR1');
        $co->scan('FR1');
        $co->scan('FR1');
        $co->scan('CF1');

        $this->assertEquals(22.45, $co->total);
    }

    /**
     * Basket: FR1,FR1
     * Total price: 3.11
     *
     * @return void
     */
    public function test_checkout2()
    {

        $co = new CheckoutService($this->pricing_rules);
        $co->scan('FR1');
        $co->scan('FR1');

        $this->assertEquals(3.11, $co->total);
    }

    /**
     * Basket: SR1,SR1,FR1,SR1
     * Total price: 16.61
     *
     * @return void
     */
    public function test_checkout3()
    {

        $co = new CheckoutService($this->pricing_rules);
        $co->scan('SR1');
        $co->scan('SR1');
        $co->scan('FR1');
        $co->scan('SR1');

        $this->assertEquals(16.61, $co->total);
    }
}
