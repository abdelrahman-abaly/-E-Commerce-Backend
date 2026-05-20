<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'minimum_order',
        'maximum_discount',
        'usage_limit',
        'usage_count',
        'usage_limit_per_user',
        'is_active',
        'starts_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'value'             => 'decimal:2',
            'minimum_order'     => 'decimal:2',
            'maximum_discount'  => 'decimal:2',
            'is_active'         => 'boolean',
            'starts_at'         => 'datetime',
            'expires_at'        => 'datetime',
        ];
    }

    // ==================== Relationships ====================

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // ==================== Helper Methods ====================



    // ==================== Scopes ====================


}
