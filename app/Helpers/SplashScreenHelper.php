<?php

namespace App\Helpers;

class SplashScreenHelper
{
    /**
     * Get a random splash screen slogan
     */
    public static function getRandomSlogan(): string
    {
        $slogans = __('common.splash_slogans');

        if (empty($slogans) || !is_array($slogans)) {
            return __('common.poetry_loading');
        }

        return $slogans[array_rand($slogans)];
    }

    /**
     * Get all available slogans
     */
    public static function getAllSlogans(): array
    {
        $slogans = __('common.splash_slogans');

        if (empty($slogans) || !is_array($slogans)) {
            return [__('common.poetry_loading')];
        }

        return $slogans;
    }

    /**
     * Get slogans count
     */
    public static function getSlogansCount(): int
    {
        return count(self::getAllSlogans());
    }
}
