<?php

namespace App\Helpers;

class AvatarHelper
{
    /**
     * Get user avatar URL with fallback
     */
    public static function getUserAvatarUrl($user)
    {
        // Check if user is null or invalid
        if (!$user || !is_object($user)) {
            return asset('assets/images/avatar/default-avatar.webp');
        }

        // Check if user has a profile photo URL (Laravel Jetstream/Fortify)
        if ($user->profile_photo_url && $user->profile_photo_url !== asset('assets/images/avatar/default-avatar.webp')) {
            return $user->profile_photo_url;
        }

        // Check if user has a custom profile photo
        if ($user->profile_photo) {
            return asset('storage/' . $user->profile_photo);
        }

        // Check if user has a custom avatar (legacy)
        if ($user->avatar) {
            return asset('storage/' . $user->avatar);
        }

        return asset('assets/images/avatar/default-avatar.webp');
    }

    /**
     * Get user avatar HTML for display
     */
    public static function getUserAvatarHtml($user, $size = 'h-40 w-40', $classes = '')
    {
        $avatarUrl = self::getUserAvatarUrl($user);
        $userName = $user && is_object($user) ? ($user->name ?? 'User') : 'User';

        return "<img src=\"{$avatarUrl}\" alt=\"{$userName}\" class=\"img-fluid {$classes}\">";
    }

    /**
     * Get user avatar HTML for JavaScript (returns string for template literals)
     */
    public static function getUserAvatarJsHtml($user)
    {
        if (!$user || !is_object($user)) {
            return "<img src=\"/assets/images/avatar/default.png\" alt=\"User\" class=\"img-fluid\">";
        }
        
        $avatarUrl = $user->avatar_url ?? '/assets/images/avatar/default.png';
        $userName = $user->name ?? 'User';
        return "<img src=\"{$avatarUrl}\" alt=\"{$userName}\" class=\"img-fluid\">";
    }

    /**
     * Get user's top 3 badges HTML for sidebar (to display before username)
     */
    public static function getUserBadgesHtml($user, $limit = 3, $size = '20')
    {
        if (!$user || !is_object($user)) {
            return '';
        }

        // Get sidebar badges only (max 3, ordered by user's choice)
        $badges = $user->badges()
            ->wherePivot('show_in_sidebar', true)
            ->orderBy('user_badges.sidebar_order')
            ->orderBy('badges.order')
            ->orderByDesc('user_badges.earned_at')
            ->limit($limit)
            ->get();

        if ($badges->isEmpty()) {
            return '';
        }

        $html = '';
        foreach ($badges as $badge) {
            $iconUrl = $badge->icon_url;
            $badgeName = htmlspecialchars($badge->name);
            $badgeDescription = htmlspecialchars($badge->description ?? '');
            
            $html .= "<img src=\"{$iconUrl}\" 
                          alt=\"{$badgeName}\" 
                          title=\"{$badgeName}: {$badgeDescription}\" 
                          class=\"badge-icon me-1\" 
                          style=\"width: {$size}px; height: {$size}px; vertical-align: middle;\" 
                          data-bs-toggle=\"tooltip\" 
                          data-bs-placement=\"top\">";
        }

        return $html;
    }

    /**
     * Get user display name with badges
     */
    public static function getUserNameWithBadges($user, $badgeSize = '20')
    {
        if (!$user || !is_object($user)) {
            return 'User';
        }

        $badges = self::getUserBadgesHtml($user, 3, $badgeSize);
        $userName = $user->getDisplayName();

        return $badges ? "{$badges} {$userName}" : $userName;
    }

    /**
     * Get user level badge HTML
     */
    public static function getUserLevelHtml($user)
    {
        if (!$user || !is_object($user) || !method_exists($user, 'userPoints')) {
            return '';
        }

        $userPoints = $user->userPoints;
        if (!$userPoints) {
            return '<span class="badge bg-light-secondary">Livello 1</span>';
        }

        $level = $userPoints->level;
        $levelName = $userPoints->current_level->name ?? "Livello {$level}";

        $colorClass = match(true) {
            $level >= 7 => 'bg-gradient-danger',
            $level >= 5 => 'bg-gradient-warning',
            $level >= 3 => 'bg-gradient-info',
            default => 'bg-light-primary'
        };

        return "<span class=\"badge {$colorClass}\" data-bs-toggle=\"tooltip\" title=\"{$levelName}\">{$levelName}</span>";
    }
}
