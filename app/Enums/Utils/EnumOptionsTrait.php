<?php

namespace App\Enums\Utils;

trait EnumOptionsTrait
{
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->translate();
        }

        return $options;
    }
}
