<?php

/**
 *
 * Requirements
 *
 * Create a simple String calculator with a method int Add(string numbers)
 * The method can take 0, 1 or 2 numbers, and will return their sum (for an empty string it will return 0) for example “” or “1” or “1,2”
 * Allow the Add method to handle an unknown amount of numbers
 * Allow the Add method to handle new lines between numbers (instead of commas).
 * The following input is ok: “1\n2,3” (will equal 6)
 * Support different delimiters
 * To change a delimiter, the beginning of the string will contain a separate line that looks like this: “//[delimiter]\n[numbers…]” for example “//;\n1;2” should return three where the default delimiter is ‘;’ .
 * The first line is optional. All existing scenarios should still be supported
 * Calling Add with a negative number will throw an exception “negatives not allowed” – and the negative that was passed. If there are multiple negatives, show all of them in the exception message stop here if you are a beginner.
 * Numbers bigger than 1000 should be ignored, so adding 2 + 1001 = 2
 * Delimiters can be of any length with the following format: “//[delimiter]\n” for example: “//[—]\n1—2—3” should return 6
 * Allow multiple delimiters like this: “//[delim1][delim2]\n” for example “//[-][%]\n1-2%3” should return 6.
 * Make sure you can also handle multiple delimiters with length longer than one char
 *
 **/

namespace Tests\Unit;

use App\Exceptions\NegativeNumberException;
use App\Services\StringCalculatorService;
use Tests\TestCase;

class StringCalculatorTest extends TestCase
{
    /**
     * Create a simple String calculator with a method int Add(string numbers)
     *
     * @return void
     */
    public function test_string_calculator_adding_method()
    {
        $result = StringCalculatorService::add('1,2,3');

        $this->assertIsInt($result);
    }

    /**
     * The method can take 0, 1 or 2 numbers, and will return their sum (for an empty string it will return 0) for example “” or “1” or “1,2”
     *
     * @return void
     */
    public function test_adding_method_to_return_sum_for_empty_string()
    {
        $result = StringCalculatorService::add('');

        $this->assertEquals(0, $result);
    }

    /**
     * The method can take 0, 1 or 2 numbers, and will return their sum (for an empty string it will return 0) for example “” or “1” or “1,2”
     *
     * @return void
     */
    public function test_adding_method_to_return_sum_for_one_input()
    {
        $result = StringCalculatorService::add('1');

        $this->assertEquals(1, $result);
    }

    /**
     * The method can take 0, 1 or 2 numbers, and will return their sum (for an empty string it will return 0) for example “” or “1” or “1,2”
     *
     * @return void
     */
    public function test_adding_method_to_return_sum_for_two_input()
    {
        $result = StringCalculatorService::add('1,2');

        $this->assertEquals(3, $result);
    }

    /**
     * Allow the Add method to handle an unknown amount of numbers
     *
     * @return void
     */
    public function test_adding_method_should_handle_an_unknown_amount_of_numbers()
    {
        $result = StringCalculatorService::add(',1,2,3,,4,5,6,7,8,9,');

        $this->assertEquals(45, $result);
    }


    /**
     * Allow the Add method to handle new lines between numbers (instead of commas).
     * The following input is ok: “1\n2,3” (will equal 6)
     *
     * @return void
     */
    public function test_adding_method_should_handle_new_lines_between_numbers()
    {
        $result = StringCalculatorService::add('1\n2,3');

        $this->assertEquals(6, $result);
    }

    /**
     * Support different delimiters.
     * To change a delimiter, the beginning of the string will contain a separate line that looks like this: “//[delimiter]\n[numbers…]” for example “//;\n1;2”
     * should return three where the default delimiter is ‘;’ .
     *
     * @return void
     */
    public function test_adding_method_should_support_different_delimiters()
    {
        $result = StringCalculatorService::add('//[;]\n1;2');

        $this->assertEquals(3, $result);
    }

    /**
     * The first line is optional. All existing scenarios should still be supported
     *
     * @return void
     */
    public function test_adding_method_should_support_first_line_is_optional()
    {
        $result = StringCalculatorService::add('

        //[;]\n1;2');

        $this->assertEquals(3, $result);
    }

    /**
     * Calling Add with a negative number will throw an exception “negatives not allowed” – and the negative that was passed.
     * If there are multiple negatives, show all of them in the exception message stop here if you are a beginner.
     *
     * @return void
     */
    public function test_adding_method_should_negative_number_will_throw_an_exception()
    {
        $this->expectException(NegativeNumberException::class);

        StringCalculatorService::add('-1');
    }

    /**
     * Numbers bigger than 1000 should be ignored, so adding 2 + 1001 = 2
     *
     * @return void
     */
    public function test_numbers_bigger_than_1000_should_be_ignored()
    {
        $result = StringCalculatorService::add('2,1001');

        $this->assertEquals(2, $result);
    }

    /**
     * Delimiters can be of any length with the following format: “//[delimiter]\n” for example: “//[—]\n1—2—3” should return 6
     *
     * @return void
     */
    public function test_delimiters_can_be_of_any_length()
    {
        $result = StringCalculatorService::add('//[—]\n1—2—3');

        $this->assertEquals(6, $result);
    }

    /**
     * Allow multiple delimiters like this: “//[delim1][delim2]\n” for example “//[-][%]\n1-2%3” should return 6.
     *
     * @return void
     */
    public function test_allow_multiple_delimiters()
    {
        $result = StringCalculatorService::add('//[-][%]\n1-2%3');

        $this->assertEquals(6, $result);
    }

    /**
     * Make sure you can also handle multiple delimiters with length longer than one char
     *
     * @return void
     */
    public function test_handle_multiple_delimiters_with_length_longer_than_one_char()
    {
        $result = StringCalculatorService::add('//[--][%]\n1--2%3');

        $this->assertEquals(6, $result);
    }

}
