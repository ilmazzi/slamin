<?php

namespace App\Helpers;

class GroupImageHelper
{
    /**
     * Get group banner URL with fallback
     */
    public static function getGroupBannerUrl($group)
    {
        // Check if group has a custom image
        if ($group->image) {
            return asset('storage/' . $group->image);
        }

        // Return random fallback image
        return self::getRandomFallbackBanner();
    }

    /**
     * Get random fallback banner image
     */
    public static function getRandomFallbackBanner()
    {
        $fallbackImages = [
            'assets/images/groups/group-banner-1.webp',
            'assets/images/groups/group-banner-2.webp',
            'assets/images/groups/group-banner-3.webp',
            'assets/images/groups/group-banner-4.webp',
            'assets/images/groups/group-banner-5.webp',
            'assets/images/groups/group-banner-6.webp'
        ];

        $randomImage = $fallbackImages[array_rand($fallbackImages)];
        return asset($randomImage);
    }

    /**
     * Get group banner HTML for display
     */
    public static function getGroupBannerHtml($group, $classes = '', $style = '')
    {
        $bannerUrl = self::getGroupBannerUrl($group);
        $groupName = $group->name ?? 'Group';

        $styleAttr = $style ? " style=\"{$style}\"" : '';
        $classesAttr = $classes ? " class=\"{$classes}\"" : '';

        return "<img src=\"{$bannerUrl}\" alt=\"{$groupName}\"{$classesAttr}{$styleAttr}>";
    }

    /**
     * Get group banner with specific dimensions
     */
    public static function getGroupBannerWithDimensions($group, $width = '100%', $height = '300px', $classes = '')
    {
        $bannerUrl = self::getGroupBannerUrl($group);
        $groupName = $group->name ?? 'Group';

        $style = "width: {$width}; height: {$height}; object-fit: cover;";
        $classesAttr = $classes ? " class=\"{$classes}\"" : '';

        return "<img src=\"{$bannerUrl}\" alt=\"{$groupName}\" style=\"{$style}\"{$classesAttr}>";
    }

    /**
     * Get group banner div with background image
     */
    public static function getGroupBannerDiv($group, $height = '300px', $classes = '')
    {
        $bannerUrl = self::getGroupBannerUrl($group);
        $groupName = $group->name ?? 'Group';

        $style = "background-image: url('{$bannerUrl}'); background-size: cover; background-position: center; height: {$height};";
        $classesAttr = $classes ? " class=\"{$classes}\"" : '';

        return "<div{$classesAttr} style=\"{$style}\" title=\"{$groupName}\"></div>";
    }
} 
