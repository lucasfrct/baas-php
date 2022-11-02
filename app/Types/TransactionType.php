<?php

namespace App\Types;

enum TransactionType: string
{
    case CashIn = 'cashin';
    case CashOut = 'cashout';
    case Bankslip = 'bankslip';
}