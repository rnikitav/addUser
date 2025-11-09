<?php

use App\Enums\UserBalanceTransactionTypeEnum;

return [
    UserBalanceTransactionTypeEnum::CREDIT->value    => 'Пополнение',
    UserBalanceTransactionTypeEnum::DEBIT->value    => 'Списание'
];
