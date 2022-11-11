<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'rg',
        'birthday',
        'gender',
        'cretificate',
        'enabled',
        'permitions',
        'without_permitions',
        'packages',
        'integrations'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birthday' => 'datetime',
        'permitions' => 'array',
        'without_permitions' => 'array',
        'packages' => 'array',
        'integrations'  => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
