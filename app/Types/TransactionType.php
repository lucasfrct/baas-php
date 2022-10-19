<?php

namespace App\Types;

enum OperatorType: string
{
    case Cashin = 'cashin';
    case Cashout = 'cashout';
    case BankSlip = 'bankSlip';
}