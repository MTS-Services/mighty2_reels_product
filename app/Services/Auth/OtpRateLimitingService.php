<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class OtpRateLimitingService
{
    private const COOLDOWN_SECONDS = 60000;
    private const HOURLY_ATTEMPTS = 500;
    private const HOUR_IN_SECONDS = 3600;

    /**
     * Check if user can resend OTP
     */
    public function canResendOtp(string $email): bool
    {
        return !$this->isInCooldown($email) && !$this->hasExceededHourlyLimit($email);
    }

    /**
     * Get remaining cooldown time in seconds
     */
    public function getCooldownRemaining(string $email): int
    {
        $cooldownKey = $this->getCooldownKey($email);
        $lastAttemptTime = Cache::get($cooldownKey);
        
        if (!$lastAttemptTime) {
            return 0;
        }

        $elapsed = Carbon::now()->diffInSeconds(Carbon::parse($lastAttemptTime));
        $remaining = max(0, self::COOLDOWN_SECONDS - $elapsed);

        return $remaining;
    }

    /**
     * Get remaining hourly attempts
     */
    public function getRemainingHourlyAttempts(string $email): int
    {
        $hourlyKey = $this->getHourlyKey($email);
        $attempts = Cache::get($hourlyKey, 0);
        
        return max(0, self::HOURLY_ATTEMPTS - $attempts);
    }

    /**
     * Get time until hourly limit resets (in seconds)
     */
    public function getHourlyResetTime(string $email): int
    {
        $hourlyKey = $this->getHourlyKey($email);
        $hourlyData = Cache::get($hourlyKey . '_data');
        
        if (!$hourlyData || !isset($hourlyData['first_attempt'])) {
            return 0;
        }

        $elapsed = Carbon::now()->diffInSeconds(Carbon::parse($hourlyData['first_attempt']));
        return max(0, self::HOUR_IN_SECONDS - $elapsed);
    }

    /**
     * Record an OTP resend attempt
     */
    public function recordAttempt(string $email): void
    {
        $this->recordCooldown($email);
        $this->recordHourlyAttempt($email);
    }

    /**
     * Get comprehensive rate limit status
     */
    public function getRateLimitStatus(string $email): array
    {
        $cooldownRemaining = $this->getCooldownRemaining($email);
        $hourlyRemaining = $this->getRemainingHourlyAttempts($email);
        $hourlyResetTime = $this->getHourlyResetTime($email);

        return [
            'can_resend' => $this->canResendOtp($email),
            'cooldown_remaining' => $cooldownRemaining,
            'hourly_attempts_remaining' => $hourlyRemaining,
            'hourly_reset_in' => $hourlyResetTime,
            'is_in_cooldown' => $cooldownRemaining > 0,
            'has_exceeded_hourly' => $hourlyRemaining <= 0 && $hourlyResetTime > 0,
        ];
    }

    /**
     * Clear all rate limiting for an email (useful for testing or admin actions)
     */
    public function clearRateLimit(string $email): void
    {
        Cache::forget($this->getCooldownKey($email));
        Cache::forget($this->getHourlyKey($email));
        Cache::forget($this->getHourlyKey($email) . '_data');
    }

    /**
     * Check if user is in cooldown period
     */
    private function isInCooldown(string $email): bool
    {
        return $this->getCooldownRemaining($email) > 0;
    }

    /**
     * Check if user has exceeded hourly limit
     */
    private function hasExceededHourlyLimit(string $email): bool
    {
        return $this->getRemainingHourlyAttempts($email) <= 0 && $this->getHourlyResetTime($email) > 0;
    }

    /**
     * Record cooldown timestamp
     */
    private function recordCooldown(string $email): void
    {
        $cooldownKey = $this->getCooldownKey($email);
        Cache::put($cooldownKey, Carbon::now()->toISOString(), self::COOLDOWN_SECONDS + 10);
    }

    /**
     * Record hourly attempt
     */
    private function recordHourlyAttempt(string $email): void
    {
        $hourlyKey = $this->getHourlyKey($email);
        $hourlyDataKey = $hourlyKey . '_data';
        
        $currentAttempts = Cache::get($hourlyKey, 0);
        $hourlyData = Cache::get($hourlyDataKey, ['first_attempt' => null, 'attempts' => 0]);
        
        // If this is the first attempt in this hour or data has expired
        if ($hourlyData['first_attempt'] === null || 
            Carbon::now()->diffInSeconds(Carbon::parse($hourlyData['first_attempt'])) >= self::HOUR_IN_SECONDS) {
            $hourlyData = [
                'first_attempt' => Carbon::now()->toISOString(),
                'attempts' => 1
            ];
            $newAttempts = 1;
        } else {
            $hourlyData['attempts']++;
            $newAttempts = $currentAttempts + 1;
        }
        
        // Calculate TTL based on time until hour resets
        $ttl = self::HOUR_IN_SECONDS - Carbon::now()->diffInSeconds(Carbon::parse($hourlyData['first_attempt'])) + 10;
        
        Cache::put($hourlyKey, $newAttempts, $ttl);
        Cache::put($hourlyDataKey, $hourlyData, $ttl);
    }

    /**
     * Get cache key for cooldown
     */
    private function getCooldownKey(string $email): string
    {
        return "otp_cooldown:" . md5($email);
    }

    /**
     * Get cache key for hourly attempts
     */
    private function getHourlyKey(string $email): string
    {
        return "otp_hourly:" . md5($email);
    }
}