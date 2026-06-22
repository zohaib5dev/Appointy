<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'category_id',
        'name',
        'slug',
        'description',
        'duration',
        'price',
        'discount_price',
        'image',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
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

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
