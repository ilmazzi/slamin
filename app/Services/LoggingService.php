<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoggingService
{
    /**
     * Log an activity
     */
    public static function log(
        string $action,
        string $category,
        string $description,
        array $details = [],
        string $level = ActivityLog::LEVEL_INFO,
        ?string $relatedModel = null,
        ?int $relatedId = null,
        ?Request $request = null
    ): ActivityLog {
        try {
            $user = Auth::user();
            $request = $request ?? request();

            $logData = [
                'user_id' => $user?->id,
                'action' => $action,
                'category' => $category,
                'description' => $description,
                'details' => $details,
                'level' => $level,
                'related_model' => $relatedModel,
                'related_id' => $relatedId,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ];

            $activityLog = ActivityLog::create($logData);

            // Also log to Laravel's default logging system for backup
            $logLevel = match($level) {
                ActivityLog::LEVEL_INFO => 'info',
                ActivityLog::LEVEL_WARNING => 'warning',
                ActivityLog::LEVEL_ERROR => 'error',
                ActivityLog::LEVEL_CRITICAL => 'critical',
                default => 'info',
            };

            Log::$logLevel("Activity Log: [{$category}] {$description}", [
                'action' => $action,
                'user_id' => $user?->id,
                'user_email' => $user?->email,
                'ip' => $request->ip(),
                'details' => $details,
            ]);

            return $activityLog;

        } catch (\Exception $e) {
            // Fallback to Laravel's default logging if our logging fails
            Log::error('Failed to create activity log', [
                'error' => $e->getMessage(),
                'action' => $action,
                'category' => $category,
                'description' => $description,
            ]);

            // Return a dummy log entry to prevent errors
            return new ActivityLog([
                'action' => $action,
                'category' => $category,
                'description' => $description,
                'level' => $level,
            ]);
        }
    }

    /**
     * Log authentication events
     */
    public static function logAuth(string $action, array $details = []): ActivityLog
    {
        $descriptions = [
            'login' => 'User logged in successfully',
            'login_failed' => 'Failed login attempt',
            'logout' => 'User logged out',
            'register' => 'New user registration',
            'password_reset' => 'Password reset requested',
            'password_changed' => 'Password changed successfully',
            'email_verified' => 'Email address verified',
        ];

        return self::log(
            "auth.{$action}",
            ActivityLog::CATEGORY_AUTH,
            $descriptions[$action] ?? "Authentication action: {$action}",
            $details,
            $action === 'login_failed' ? ActivityLog::LEVEL_WARNING : ActivityLog::LEVEL_INFO
        );
    }

    /**
     * Log user management events
     */
    public static function logUser(string $action, array $details = [], ?string $relatedModel = null, ?int $relatedId = null): ActivityLog
    {
        $descriptions = [
            'create' => 'New user created',
            'update' => 'User profile updated',
            'delete' => 'User account deleted',
            'role_assigned' => 'Role assigned to user',
            'role_removed' => 'Role removed from user',
            'permission_granted' => 'Permission granted to user',
            'permission_revoked' => 'Permission revoked from user',
            'status_changed' => 'User status changed',
            'profile_photo_updated' => 'Profile photo updated',
            'banner_updated' => 'Banner image updated',
        ];

        return self::log(
            "user.{$action}",
            ActivityLog::CATEGORY_USERS,
            $descriptions[$action] ?? "User action: {$action}",
            $details,
            ActivityLog::LEVEL_INFO,
            $relatedModel,
            $relatedId
        );
    }

    /**
     * Log event management events
     */
    public static function logEvent(string $action, array $details = [], ?string $relatedModel = null, ?int $relatedId = null): ActivityLog
    {
        $descriptions = [
            'create' => 'New event created',
            'update' => 'Event updated',
            'delete' => 'Event deleted',
            'publish' => 'Event published',
            'unpublish' => 'Event unpublished',
            'invite_sent' => 'Event invitation sent',
            'invite_accepted' => 'Event invitation accepted',
            'invite_declined' => 'Event invitation declined',
            'request_submitted' => 'Event participation request submitted',
            'request_approved' => 'Event participation request approved',
            'request_rejected' => 'Event participation request rejected',
        ];

        return self::log(
            "event.{$action}",
            ActivityLog::CATEGORY_EVENTS,
            $descriptions[$action] ?? "Event action: {$action}",
            $details,
            ActivityLog::LEVEL_INFO,
            $relatedModel,
            $relatedId
        );
    }

    /**
     * Log video management events
     */
    public static function logVideo(string $action, array $details = [], ?string $relatedModel = null, ?int $relatedId = null): ActivityLog
    {
        $descriptions = [
            'upload' => 'Video uploaded',
            'update' => 'Video updated',
            'delete' => 'Video deleted',
            'publish' => 'Video published',
            'unpublish' => 'Video unpublished',
            'view' => 'Video viewed',
            'like' => 'Video liked',
            'unlike' => 'Video unliked',
            'comment' => 'Comment added to video',
            'comment_deleted' => 'Comment deleted from video',
            'snap' => 'Snap added to video',
            'snap_deleted' => 'Snap deleted from video',
            'download' => 'Video downloaded',
        ];

        return self::log(
            "video.{$action}",
            ActivityLog::CATEGORY_VIDEOS,
            $descriptions[$action] ?? "Video action: {$action}",
            $details,
            ActivityLog::LEVEL_INFO,
            $relatedModel,
            $relatedId
        );
    }

    /**
     * Log admin panel events
     */
    public static function logAdmin(string $action, array $details = [], ?string $relatedModel = null, ?int $relatedId = null): ActivityLog
    {
        $descriptions = [
            'dashboard_access' => 'Admin dashboard accessed',
            'user_management' => 'User management action performed',
            'system_settings' => 'System settings modified',
            'carousel_management' => 'Carousel content managed',
            'translation_management' => 'Translation management action',
            'permission_management' => 'Permission management action',
            'analytics_view' => 'Analytics viewed',
            'log_view' => 'System logs viewed',
            'backup_created' => 'System backup created',
            'maintenance_mode' => 'Maintenance mode toggled',
        ];

        return self::log(
            "admin.{$action}",
            ActivityLog::CATEGORY_ADMIN,
            $descriptions[$action] ?? "Admin action: {$action}",
            $details,
            ActivityLog::LEVEL_INFO,
            $relatedModel,
            $relatedId
        );
    }

    /**
     * Log system events
     */
    public static function logSystem(string $action, array $details = [], string $level = ActivityLog::LEVEL_INFO): ActivityLog
    {
        $descriptions = [
            'maintenance_start' => 'System maintenance started',
            'maintenance_end' => 'System maintenance completed',
            'backup_start' => 'System backup started',
            'backup_complete' => 'System backup completed',
            'backup_failed' => 'System backup failed',
            'cache_cleared' => 'System cache cleared',
            'queue_failed' => 'Queue job failed',
            'disk_space_low' => 'Low disk space detected',
            'memory_high' => 'High memory usage detected',
            'error_threshold' => 'Error threshold exceeded',
        ];

        return self::log(
            "system.{$action}",
            ActivityLog::CATEGORY_SYSTEM,
            $descriptions[$action] ?? "System action: {$action}",
            $details,
            $level
        );
    }

    /**
     * Log premium/subscription events
     */
    public static function logPremium(string $action, array $details = [], ?string $relatedModel = null, ?int $relatedId = null): ActivityLog
    {
        $descriptions = [
            'subscription_created' => 'Premium subscription created',
            'subscription_updated' => 'Premium subscription updated',
            'subscription_cancelled' => 'Premium subscription cancelled',
            'subscription_renewed' => 'Premium subscription renewed',
            'payment_success' => 'Payment processed successfully',
            'payment_failed' => 'Payment processing failed',
            'payment_refunded' => 'Payment refunded',
            'trial_started' => 'Premium trial started',
            'trial_ended' => 'Premium trial ended',
        ];

        return self::log(
            "premium.{$action}",
            ActivityLog::CATEGORY_PREMIUM,
            $descriptions[$action] ?? "Premium action: {$action}",
            $details,
            ActivityLog::LEVEL_INFO,
            $relatedModel,
            $relatedId
        );
    }

    /**
     * Log permission/role events
     */
    public static function logPermission(string $action, array $details = [], ?string $relatedModel = null, ?int $relatedId = null): ActivityLog
    {
        $descriptions = [
            'role_created' => 'New role created',
            'role_updated' => 'Role updated',
            'role_deleted' => 'Role deleted',
            'permission_created' => 'New permission created',
            'permission_updated' => 'Permission updated',
            'permission_deleted' => 'Permission deleted',
            'role_permission_assigned' => 'Permission assigned to role',
            'role_permission_removed' => 'Permission removed from role',
            'user_role_assigned' => 'Role assigned to user',
            'user_role_removed' => 'Role removed from user',
        ];

        return self::log(
            "permission.{$action}",
            ActivityLog::CATEGORY_PERMISSIONS,
            $descriptions[$action] ?? "Permission action: {$action}",
            $details,
            ActivityLog::LEVEL_INFO,
            $relatedModel,
            $relatedId
        );
    }

    /**
     * Log carousel management events
     */
    public static function logCarousel(string $action, array $details = [], ?string $relatedModel = null, ?int $relatedId = null): ActivityLog
    {
        $descriptions = [
            'create' => 'Carousel item created',
            'update' => 'Carousel item updated',
            'delete' => 'Carousel item deleted',
            'reorder' => 'Carousel items reordered',
            'activate' => 'Carousel item activated',
            'deactivate' => 'Carousel item deactivated',
        ];

        return self::log(
            "carousel.{$action}",
            ActivityLog::CATEGORY_CAROUSEL,
            $descriptions[$action] ?? "Carousel action: {$action}",
            $details,
            ActivityLog::LEVEL_INFO,
            $relatedModel,
            $relatedId
        );
    }

    /**
     * Log translation management events
     */
    public static function logTranslation(string $action, array $details = [], ?string $relatedModel = null, ?int $relatedId = null): ActivityLog
    {
        $descriptions = [
            'translation_updated' => 'Translation updated',
            'translation_created' => 'New translation created',
            'translation_deleted' => 'Translation deleted',
            'auto_translation' => 'Automatic translation performed',
            'translation_imported' => 'Translations imported',
            'translation_exported' => 'Translations exported',
        ];

        return self::log(
            "translation.{$action}",
            ActivityLog::CATEGORY_TRANSLATIONS,
            $descriptions[$action] ?? "Translation action: {$action}",
            $details,
            ActivityLog::LEVEL_INFO,
            $relatedModel,
            $relatedId
        );
    }

    /**
     * Log settings management events
     */
    public static function logSettings(string $action, array $details = [], ?string $relatedModel = null, ?int $relatedId = null): ActivityLog
    {
        $descriptions = [
            'setting_updated' => 'System setting updated',
            'setting_created' => 'New system setting created',
            'setting_deleted' => 'System setting deleted',
            'settings_imported' => 'Settings imported',
            'settings_exported' => 'Settings exported',
            'settings_reset' => 'Settings reset to defaults',
        ];

        return self::log(
            "settings.{$action}",
            ActivityLog::CATEGORY_SETTINGS,
            $descriptions[$action] ?? "Settings action: {$action}",
            $details,
            ActivityLog::LEVEL_INFO,
            $relatedModel,
            $relatedId
        );
    }

    /**
     * Log error events
     */
    public static function logError(string $action, array $details = [], ?string $relatedModel = null, ?int $relatedId = null): ActivityLog
    {
        $descriptions = [
            'validation_failed' => 'Validation failed',
            'database_error' => 'Database error occurred',
            'file_upload_failed' => 'File upload failed',
            'external_api_error' => 'External API error',
            'permission_denied' => 'Permission denied',
            'not_found' => 'Resource not found',
            'server_error' => 'Server error occurred',
        ];

        return self::log(
            "error.{$action}",
            ActivityLog::CATEGORY_SYSTEM,
            $descriptions[$action] ?? "Error: {$action}",
            $details,
            ActivityLog::LEVEL_ERROR,
            $relatedModel,
            $relatedId
        );
    }
} 