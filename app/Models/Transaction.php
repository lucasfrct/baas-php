<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uid',
        'amount',
        'payer_document',
        'payer_uuid',
        'payer_bank_name',
        'payer_bank_code',
        'payer_bank_ispb',
        'payer_bank_branch',
        'payer_bank_number',
        'payer_bank_operator',
        'receipient_document',
        'receipient_uuid',
        'receipient_bank_name',
        'receipient_bank_code',
        'receipient_bank_ispb',
        'receipient_bank_branch',
        'receipient_bank_number',
        'receipient_bank_operator',
        'packages',
        'tax_amount',
        'type',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'packages'  => 'array'
    ];
}
