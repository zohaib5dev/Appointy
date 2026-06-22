<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'staff_id',
        'day',
        'start_time',
        'end_time',
        'is_active',
    ];

      protected $casts = [
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

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function breaks()
    {
        return $this->hasMany(BreakTime::class);
    }
}
