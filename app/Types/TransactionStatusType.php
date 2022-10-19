<?php

namespace App\Types;

enum TransactionStatusType: string
{
    case Transient = 'transient';
    case Error = 'error';
    case Denied = 'denied';
    case Incomplete = 'incomplete';
    case Processing = 'processing';
    case Paided = 'paided';
    case Canceled = 'canceled';

}