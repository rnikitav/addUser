<?php

namespace App\Enums;

use App\Enums\Utils\EnumOptionsTrait;
use App\Enums\Utils\EnumValuesTrait;
use App\Enums\Utils\TranslateInterface;

enum UserBalanceTransactionTypeEnum: string implements TranslateInterface
{
    use EnumValuesTrait;
    use EnumOptionsTrait;

    case CREDIT = 'credit';
    case DEBIT = 'debit';


    public function translate(): string
    {
        return trans('enums/UserBalanceTransactionType.' . $this->value);
    }
}
