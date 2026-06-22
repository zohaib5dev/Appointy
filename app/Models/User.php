<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Appointment;
use App\Models\SystemNotification;
use App\Models\Vendor;
use App\Models\Review;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'timezone',
        'profile_photo',
        'status',
        'address',
        'city',
        'state',
        'zip',
        'country',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'customer_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    public function notifications()
    {
        return $this->hasMany(SystemNotification::class);
    }

     public function isSuperAdmin()
    {
        return $this->role === 'super_admin' || $this->hasRole('super_admin');
    }

    public function isAdmin()
    {
        return $this->role === 'admin' || $this->hasRole('admin');
    }

    public function isVendor()
    {
        return $this->role === 'vendor' || $this->hasRole('vendor');
    }

    public function isCustomer()
    {
        return $this->role === 'customer' || $this->hasRole('customer');
    }

    public function isStaff()
    {
        return $this->role === 'staff' || $this->hasRole('staff');
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

}
