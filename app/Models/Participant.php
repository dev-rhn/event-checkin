<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Participant extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'unique_code',
        'is_checked_in',
        'checked_in_at',
    ];

    protected $casts = [
        'is_checked_in' => 'boolean',
        'checked_in_at' => 'datetime',
    ];

    // Auto-generate unique code saat create
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($participant) {
            if (empty($participant->unique_code)) {
                $participant->unique_code = Str::uuid()->toString();
            }
        });
    }
}