<?php

use App\Helpers\GroupImageHelper;
use App\Helpers\AvatarHelper;

if (!function_exists('group_banner_url')) {
    function group_banner_url($group) {
        return GroupImageHelper::getGroupBannerUrl($group);
    }
}

if (!function_exists('group_banner_html')) {
    function group_banner_html($group, $classes = '', $style = '') {
        return GroupImageHelper::getGroupBannerHtml($group, $classes, $style);
    }
}

if (!function_exists('group_banner_with_dimensions')) {
    function group_banner_with_dimensions($group, $width = '100%', $height = '300px', $classes = '') {
        return GroupImageHelper::getGroupBannerWithDimensions($group, $width, $height, $classes);
    }
}

if (!function_exists('getUserAvatarHtml')) {
    function getUserAvatarHtml($user, $size = 'h-40 w-40', $classes = '') {
        return AvatarHelper::getUserAvatarHtml($user, $size, $classes);
    }
}

if (!function_exists('getUserStatusClass')) {
    function getUserStatusClass($user) {
        if (!$user) return 'bg-secondary';

        $status = $user->online_status ?? 'offline';

        return match($status) {
            'online' => 'bg-success',
            'away' => 'bg-warning',
            'busy' => 'bg-danger',
            default => 'bg-secondary'
        };
    }
}
