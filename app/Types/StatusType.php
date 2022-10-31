<?php

namespace App\Types;

enum StatusType: int
{
    case Enabled = 1;
    case Disabled = 0;
}