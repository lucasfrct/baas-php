<?php

namespace App\Types;

enum OperatorType: int
{
    case Savings = 0;
    case Checking = 1;
    case Vgbl = 2;
}