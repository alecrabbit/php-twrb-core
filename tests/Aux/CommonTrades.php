<?php
/**
 * Date: 19.11.18
 * Time: 20:09
 */

namespace AlecRabbit\Tests\Aux;

use AlecRabbit\Accessories\Circular;
use AlecRabbit\TWRBCore\DataStruct\Trade;

class CommonTrades
{
    public static function generator(
        Circular $type,
        Circular $pair,
        Circular $price,
        Circular $amount,
        $quantity = 5760,
        int $step = 15,
        int $timestamp = 1514764800
    ): ?\Generator {
        while ($quantity-- > 0) {
            yield
            new Trade(
                $type->value(),
                $pair->value(),
                $price->value(),
                $amount->value(),
                $timestamp
            );
            $timestamp += $step;
        }
    }
}
