<?php

namespace App\Shared;

class Str{
    public static function padDocument(string $document): string {
        return str_pad($document, 14, "X", STR_PAD_LEFT);
    }

    public static function padTimestamp(int | string $timestamp): string {
        return str_pad($timestamp, 10, '0', STR_PAD_LEFT);
    }
}