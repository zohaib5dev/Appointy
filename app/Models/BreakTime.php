<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class BreakTime extends Model
{
    use HasFactory;

    protected $table = 'breaks';

    protected $fillable = [
        'vendor_id',
        'staff_id',
        'start_time',
        'end_time',
        'day_of_week',
        'is_active'
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

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
