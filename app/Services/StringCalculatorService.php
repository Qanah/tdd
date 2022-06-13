<?php

namespace App\Services;

use App\Exceptions\NegativeNumberException;

class StringCalculatorService
{

    public static function add(string $numbers): int {

        $numbers = trim($numbers);

        list($delimiters, $numbers) = self::extract_delimiters($numbers);

        $numbers = preg_split('/('.implode('|', $delimiters).')/', str_replace('\n', ',', $numbers));

        $total = 0;

        foreach ($numbers as $number) {

            $number = intval($number);

            if($number < 0) {
                throw new NegativeNumberException();
            }

            if($number > 1000) {
                $number = 0;
            }

            $total += $number;
        }

        return $total;
    }

    private static function extract_delimiters(string $numbers) {

        $delimiters = [','];

        //[delimiter][delimiter]\n[numbers…]

        if(strpos($numbers,'//', 0) !== false) {

            $explodeDelimiters = explode('\n', $numbers);

            preg_match_all("/\[[^\]]*\]/", ($explodeDelimiters[0] ?? ''), $matches);

            foreach ($matches[0] as $match) {
                $delimiters[] = str_replace(['[',']'], '', $match);
            }

            $numbers = $explodeDelimiters[1] ?? '';
        }

        return [$delimiters, $numbers];
    }

}
