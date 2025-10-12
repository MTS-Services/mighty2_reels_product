<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Status constants
     */
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    /**
     * Admin constants
     */
    public const IS_ADMIN = 1;
    public const NOT_ADMIN = 0;

    /**
     * Gender constants
     */
    public const GENDER_MALE = 1;
    public const GENDER_FEMALE = 2;
    public const GENDER_OTHER = 3;

    public const GENDERS = [
        self::GENDER_MALE => 'Male',
        self::GENDER_FEMALE => 'Female',
        self::GENDER_OTHER => 'Other',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'street',
        'is_admin',
        'status',
        'suburb',
        'postcode',
        'state',
        'password',
        'otp',
        'expires_at',
        'otp_token',
        'photo',
        'provider',
        'provider_id',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'otp_token',
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
            'expires_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Appended attributes
     */
    protected $appends = [
        'full_name'
    ];

    /**
     * Get the user's full name
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $name = $this->full_name;
        return Str::of($name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Generate OTP for email verification
     */
    public function generateOtp(): void
    {
        $this->otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->otp_token = Str::random(60);
        $this->expires_at = Carbon::now()->addMinutes(10);
        $this->save();

        // Send OTP via email
        $this->notify(new \App\Notifications\SendOtpNotification($this->otp));
    }

    /**
     * Verify OTP
     * 
     * @param string $otp
     * @return bool
     */
    public function verifyOtp(string $otp): bool
    {
        if ($this->otp === $otp && $this->expires_at && $this->expires_at->isFuture()) {
            $this->email_verified_at = now();
            $this->otp = null;
            $this->expires_at = null;
            $this->otp_token = null;
            $this->save();
            
            return true;
        }

        return false;
    }

    /**
     * Check if OTP is expired
     * 
     * @return bool
     */
    public function isOtpExpired(): bool
    {
        if (!$this->expires_at) {
            return true;
        }

        return $this->expires_at->isPast();
    }

    /**
     * Clear OTP data
     */
    public function clearOtp(): void
    {
        $this->otp = null;
        $this->expires_at = null;
        $this->otp_token = null;
        $this->save();
    }

    /**
     * Relationship: User has many favorite reels
     */
    public function favouriteReels()
    {
        return $this->hasMany(FavouretReel::class);
    }

    /**
     * Relationship: User has many favorite products through favorite reels
     */
    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'favouret_reels', 'user_id', 'product_id')
                    ->withTimestamps()
                    ->withPivot(['created_by', 'updated_by', 'deleted_by']);
    }

    /**
     * Check if user has favorited a specific product
     * 
     * @param int $productId
     * @return bool
     */
    public function hasFavorited($productId): bool
    {
        return $this->favouriteReels()
                    ->where('product_id', $productId)
                    ->exists();
    }

    /**
     * Scope: Active users
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope: Admin users
     */
    public function scopeAdmin($query)
    {
        return $query->where('is_admin', self::IS_ADMIN);
    }

    /**
     * Scope: Verified users
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Check if user is admin
     * 
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === self::IS_ADMIN;
    }

    /**
     * Check if user is active
     * 
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}