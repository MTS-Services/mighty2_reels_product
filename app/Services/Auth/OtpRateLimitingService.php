<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class OtpRateLimitingService
{
    private const COOLDOWN_SECONDS = 20;
    private const HOURLY_ATTEMPTS = 500;
    private const HOUR_IN_SECONDS = 3600;

    public function canResendOtp(string $email): bool
    {
        return !$this->isInCooldown($email) && !$this->hasExceededHourlyLimit($email);
    }

    public function getCooldownRemaining(string $email): int
    {
        $cooldownKey = $this->getCooldownKey($email);
        $lastAttemptTime = Cache::get($cooldownKey);

        if (!$lastAttemptTime) {
            return 0;
        }

        $elapsed = Carbon::now()->diffInSeconds(Carbon::parse($lastAttemptTime));
        return max(0, self::COOLDOWN_SECONDS - $elapsed);
    }

    public function getRemainingHourlyAttempts(string $email): int
    {
        $hourlyKey = $this->getHourlyKey($email);
        $hourlyData = Cache::get($hourlyKey);

        if (!$hourlyData) {
            return self::HOURLY_ATTEMPTS;
        }

        // Also check if the time window has passed
        if (Carbon::now()->diffInSeconds(Carbon::parse($hourlyData['first_attempt'])) >= self::HOUR_IN_SECONDS) {
            Cache::forget($hourlyKey); // Clean up expired key
            return self::HOURLY_ATTEMPTS;
        }

        return max(0, self::HOURLY_ATTEMPTS - $hourlyData['attempts']);
    }

    public function getHourlyResetTime(string $email): int
    {
        $hourlyKey = $this->getHourlyKey($email);
        $hourlyData = Cache::get($hourlyKey);

        if (!$hourlyData) {
            return 0;
        }

        $elapsed = Carbon::now()->diffInSeconds(Carbon::parse($hourlyData['first_attempt']));

        // If the window has already passed, reset time is 0
        if ($elapsed >= self::HOUR_IN_SECONDS) {
            Cache::forget($hourlyKey); // Clean up expired key
            return 0;
        }
        
        return max(0, self::HOUR_IN_SECONDS - $elapsed);
    }

    public function recordAttempt(string $email): void
    {
        $this->recordCooldown($email);
        $this->recordHourlyAttempt($email);
    }

    public function getRateLimitStatus(string $email): array
    {
        $cooldownRemaining = $this->getCooldownRemaining($email);
        $hourlyRemaining = $this->getRemainingHourlyAttempts($email);
        $hourlyResetTime = $this->getHourlyResetTime($email);

        return [
            'can_resend' => $cooldownRemaining <= 0 && $hourlyRemaining > 0,
            'cooldown_remaining' => $cooldownRemaining,
            'hourly_attempts_remaining' => $hourlyRemaining,
            'hourly_reset_in' => $hourlyResetTime,
            'is_in_cooldown' => $cooldownRemaining > 0,
            'has_exceeded_hourly' => $hourlyRemaining <= 0 && $hourlyResetTime > 0,
        ];
    }

    public function clearRateLimit(string $email): void
    {
        Cache::forget($this->getCooldownKey($email));
        Cache::forget($this->getHourlyKey($email));
    }

    private function isInCooldown(string $email): bool
    {
        return $this->getCooldownRemaining($email) > 0;
    }

    private function hasExceededHourlyLimit(string $email): bool
    {
        return $this->getRemainingHourlyAttempts($email) <= 0;
    }

    private function recordCooldown(string $email): void
    {
        $cooldownKey = $this->getCooldownKey($email);
        // Add a small buffer to the TTL to prevent race conditions
        Cache::put($cooldownKey, Carbon::now()->toISOString(), self::COOLDOWN_SECONDS + 5);
    }

    /**
     * Record hourly attempt using a single, unified cache key.
     */
    private function recordHourlyAttempt(string $email): void
    {
        $hourlyKey = $this->getHourlyKey($email);
        $hourlyData = Cache::get($hourlyKey);

        $now = Carbon::now();

        // If no record exists or the record is from a previous hour
        if (!$hourlyData || $now->diffInSeconds(Carbon::parse($hourlyData['first_attempt'])) >= self::HOUR_IN_SECONDS) {
            $hourlyData = [
                'first_attempt' => $now->toISOString(),
                'attempts' => 1,
            ];
        } else {
            $hourlyData['attempts']++;
        }
        
        // Calculate the remaining time for the cache TTL, add a small buffer
        $ttl = self::HOUR_IN_SECONDS - $now->diffInSeconds(Carbon::parse($hourlyData['first_attempt'])) + 5;
        
        Cache::put($hourlyKey, $hourlyData, $ttl);
    }

    private function getCooldownKey(string $email): string
    {
        return "otp_cooldown:" . md5($email);
    }

    private function getHourlyKey(string $email): string
    {
        return "otp_hourly:" . md5($email);
    }
}