<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

   protected $fillable = [
        'user_id',
        'slug',
        'status',
        'subscription_status',
        'stripe_customer_id',
        'stripe_subscription_id',
        'stripe_account_id',
        'stripe_account_status',
        'plan_id',
        'price_id',
        'trial_ends_at',
        'subscription_starts_at',
        'subscription_ends_at',
        'business_name',
        'description',
        'phone',
        'website',
        'timezone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'logo',
        'cover_image',
        'is_verified',
        'verified_at',
        'commission_rate',
        'earnings',
        'rating',
        'total_reviews',
        'settings',
        'social_links',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'earnings' => 'decimal:2',
        'rating' => 'decimal:2',
        'settings' => 'array',
        'social_links' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
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

    protected function subscriptionEndsAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? \Carbon\Carbon::parse($value)->format('M j, Y') : null,
        );
    }

    protected function trialEndsAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? \Carbon\Carbon::parse($value)->format('M j, Y') : null,
        );
    }
  
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function breaks()
    {
        return $this->hasMany(BreakTime::class);
    }

    public function holidays()
    {
        return $this->hasMany(Holiday::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function plan_price()
    {
        return $this->belongsTo(PlanPrice::class,'price_id');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function getRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}