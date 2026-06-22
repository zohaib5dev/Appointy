<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'services_limit',
        'staff_limit',
        'appointments_limit',
        'storage_limit',
        'is_active'
    ];

        protected $casts = [
        'is_active' => 'boolean',
        'services_limit' => 'integer',
        'staff_limit' => 'integer',
        'appointments_limit' => 'integer',
        'storage_limit' => 'integer',
    ];

    public function prices()
    {
        return $this->hasMany(PlanPrice::class);
    }


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

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
