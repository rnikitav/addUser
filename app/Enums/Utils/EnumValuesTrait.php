<?php

namespace App\Enums\Utils;

trait EnumValuesTrait
{
    public static function getValues(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }
}
