<?php

use App\Helpers\GroupImageHelper;

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