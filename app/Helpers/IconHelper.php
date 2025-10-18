<?php

namespace App\Helpers;

class IconHelper
{
    /**
     * Get icon path for a specific feature
     */
    public static function getIcon($feature, $size = '24', $fallback = true)
    {
        $iconMap = [
            'dashboard' => 'dashboard.svg',
            'snap' => 'snap.svg',
            'event' => 'event.svg',
            'learn' => 'learn.svg',
            'gigs' => '005-togetherness.png',
            'news' => 'news.svg',
            'media' => 'media.svg',
            'poetry' => 'poetry.png', // Using avatar for poetry for now
            'profile' => '014-user.png',
            'team' => '009-team.png',
            'shortcuts' => '010-speedometer.png',
            'calendar' => '011-calendar.png',
            'edit-profile' => '012-human-resources.png',
            'admin' => '012-human-resources.png', // Using same as edit-profile
            'notification' => '016-account-activity.png', // Using activity for notification
            'picture' => '013-picture.png',
            'edit' => '015-writing.png',
            'activity' => '016-account-activity.png',
            'settings' => '017-setting.png',
            'email' => '018-mail.png',
            'like' => '002-kpop.png', // Using snap/heart for like
            'wiki' => '019-collective.png',
            'forum' => '020-group.png',
            'article' => '021-content.png',
            'permissions' => 'lock.png',
            'moderation' => 'self-regulation.png',
            'payment' => 'payment-method.png',
            'peertube' => 'online-video.png',
            'kanban' => 'kanban.png',
            'logs' => 'log.png',
        ];

        $iconFile = $iconMap[$feature] ?? null;

        if ($iconFile && file_exists(public_path("assets/icon/new/{$iconFile}"))) {
            return asset("assets/icon/new/{$iconFile}");
        }

        // Fallback to existing icons or default
        if ($fallback) {
            return self::getFallbackIcon($feature);
        }

        return null;
    }

    /**
     * Get fallback icon using existing system
     */
    private static function getFallbackIcon($feature)
    {
        $fallbackMap = [
            'dashboard' => 'ph ph-chart-line',
            'snap' => 'ph ph-heart',
            'event' => 'ph ph-microphone',
            'learn' => 'ph ph-graduation-cap',
            'gigs' => 'ph ph-users',
            'news' => 'ph ph-newspaper',
            'media' => 'ph ph-camera',
            'poetry' => 'ph ph-book',
            'profile' => 'ph ph-user',
            'team' => 'ph ph-users-three',
            'shortcuts' => 'ph ph-lightning',
            'calendar' => 'ph ph-calendar',
            'edit-profile' => 'ph ph-user-circle',
            'admin' => 'ph ph-gear',
            'notification' => 'ph ph-bell',
            'picture' => 'ph ph-image',
            'edit' => 'ph ph-pencil',
            'activity' => 'ph ph-activity',
            'settings' => 'ph ph-gear',
            'email' => 'ph ph-envelope',
            'like' => 'ph ph-heart',
            'wiki' => 'ph ph-book-open',
            'forum' => 'ph ph-chat-circle',
            'article' => 'ph ph-newspaper',
        ];

        return $fallbackMap[$feature] ?? 'ph ph-circle';
    }

    /**
     * Get icon HTML with proper styling
     */
    public static function getIconHtml($feature, $size = '24', $class = '', $fallback = true)
    {
        $iconPath = self::getIcon($feature, $size, $fallback);

        if ($iconPath && str_contains($iconPath, '.svg')) {
            // It's an SVG file
            return sprintf(
                '<img src="%s" alt="%s" class="icon-%s %s" style="width: %spx; height: %spx;">',
                $iconPath,
                $feature,
                $feature,
                $class,
                $size,
                $size
            );
        } elseif ($iconPath && !str_contains($iconPath, 'ph ph-')) {
            // It's an image file (PNG, JPG, etc.)
            return sprintf(
                '<img src="%s" alt="%s" class="icon-%s %s" style="width: %spx; height: %spx;">',
                $iconPath,
                $feature,
                $feature,
                $class,
                $size,
                $size
            );
        } else {
            // It's a Phosphor icon
            $iconClass = $iconPath ?: 'ph ph-circle';
            return sprintf(
                '<i class="%s %s" style="font-size: %spx;"></i>',
                $iconClass,
                $class,
                $size
            );
        }
    }

    /**
     * Get all available icons
     */
    public static function getAvailableIcons()
    {
        $iconsDir = public_path('assets/icon/new');
        $icons = [];

        if (is_dir($iconsDir)) {
            $files = scandir($iconsDir);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'png') {
                    $icons[] = pathinfo($file, PATHINFO_FILENAME);
                }
            }
        }

        return $icons;
    }
}
