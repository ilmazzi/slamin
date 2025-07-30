<?php

namespace App\Helpers;

class AvatarHelper
{
    /**
     * Get user avatar URL with fallback
     */
    public static function getUserAvatarUrl($user)
    {
        if ($user->avatar) {
            return asset('storage/' . $user->avatar);
        }
        
        return asset('assets/images/avatar/default.png');
    }

    /**
     * Get user avatar HTML for display
     */
    public static function getUserAvatarHtml($user, $size = 'h-40 w-40', $classes = '')
    {
        $avatarUrl = self::getUserAvatarUrl($user);
        $userName = $user->name ?? 'User';
        
        return "<img src=\"{$avatarUrl}\" alt=\"{$userName}\" class=\"img-fluid {$classes}\">";
    }

    /**
     * Get user avatar HTML for JavaScript (returns string for template literals)
     */
    public static function getUserAvatarJsHtml($user)
    {
        $avatarUrl = $user->avatar_url ?? '/assets/images/avatar/default.png';
        return "<img src=\"{$avatarUrl}\" alt=\"{$user->name}\" class=\"img-fluid\">";
    }
} 