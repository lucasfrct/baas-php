<?php

namespace App\Types;

enum OperatorType: string
{
    case CashIn = 'cashin';
    case CashOut = 'cashout';
    case Bankslip = 'bankslip';
}