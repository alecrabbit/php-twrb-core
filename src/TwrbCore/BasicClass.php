<?php declare(strict_types=1);

namespace AlecRabbit\TwrbCore;

class BasicClass
{
    public static function get(int $var = 10): bool
    {
        if ($var === 10) {
            return true;
        }
        return false;
    }
}
