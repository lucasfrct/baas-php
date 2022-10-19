<?php

namespace App\Shared;

class Str{
    public static function padDocument(string $document): string {
        return str_pad($document, 14, "X", STR_PAD_LEFT);
    }

    public static function padTimestamp(int | string $timestamp): string {
        return str_pad($timestamp, 10, '0', STR_PAD_LEFT);
    }

    public static function padBankAccountNumber(int | string $number): string {
        return str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    public static function padCoreIp(string $business, string $cnpj): string {
        return strtoupper(substr($business, 0, 3)).str_pad($cnpj, 14, "X", STR_PAD_LEFT);
    }

    public static function padSubIp(string $coreIp, int | string $filial): string {
        return $coreIp.str_pad($filial, 2, "0", STR_PAD_LEFT);
    }
}