<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'code',
        'type',
        'value',
        'min_spend',
        'usage_limit',
        'used_count',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

     protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? \Carbon\Carbon::parse($value)->format('M j, Y h:i A') : null,
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? \Carbon\Carbon::parse($value)->format('M j, Y h:i A') : null,
        );
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
