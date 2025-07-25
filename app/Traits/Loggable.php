<?php

namespace App\Traits;

use App\Services\LoggingService;
use Illuminate\Http\Request;

trait Loggable
{
    /**
     * Log a successful operation
     */
    protected function logSuccess(string $action, array $details = [], ?string $relatedModel = null, ?int $relatedId = null)
    {
        try {
            $category = $this->getLogCategory();
            LoggingService::log($action, $category, $this->getLogDescription($action), $details, 'info', $relatedModel, $relatedId);
        } catch (\Exception $e) {
            // Silently fail to avoid breaking the application
            \Log::error('Failed to log success action', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Log an error operation
     */
    protected function logError(string $action, array $details = [], ?string $relatedModel = null, ?int $relatedId = null)
    {
        try {
            $category = $this->getLogCategory();
            LoggingService::log($action, $category, $this->getLogDescription($action), $details, 'error', $relatedModel, $relatedId);
        } catch (\Exception $e) {
            // Silently fail to avoid breaking the application
            \Log::error('Failed to log error action', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Log a warning operation
     */
    protected function logWarning(string $action, array $details = [], ?string $relatedModel = null, ?int $relatedId = null)
    {
        try {
            $category = $this->getLogCategory();
            LoggingService::log($action, $category, $this->getLogDescription($action), $details, 'warning', $relatedModel, $relatedId);
        } catch (\Exception $e) {
            // Silently fail to avoid breaking the application
            \Log::error('Failed to log warning action', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get the log category for this controller
     */
    protected function getLogCategory(): string
    {
        $className = class_basename($this);
        
        return match($className) {
            'AuthController' => 'authentication',
            'EventController' => 'events',
            'VideoController' => 'videos',
            'ProfileController' => 'users',
            'PermissionController' => 'permissions',
            'CarouselController' => 'carousel',
            'TranslationController' => 'translations',
            'SystemSettingsController' => 'settings',
            'PremiumController' => 'premium',
            'MediaController' => 'media',
            'InvitationController' => 'events',
            'AnalyticsController' => 'admin',
            default => 'system',
        };
    }

    /**
     * Get a human-readable description for the action
     */
    protected function getLogDescription(string $action): string
    {
        $descriptions = [
            // Auth actions
            'login' => 'User logged in successfully',
            'login_failed' => 'Failed login attempt',
            'logout' => 'User logged out',
            'register' => 'New user registration',
            
            // CRUD actions
            'create' => 'New record created',
            'store' => 'New record stored',
            'read' => 'Record viewed',
            'show' => 'Record details viewed',
            'update' => 'Record updated',
            'edit' => 'Record edit form accessed',
            'delete' => 'Record deleted',
            'destroy' => 'Record destroyed',
            
            // Event actions
            'event_create' => 'New event created',
            'event_update' => 'Event updated',
            'event_delete' => 'Event deleted',
            'event_publish' => 'Event published',
            'event_unpublish' => 'Event unpublished',
            'event_invite' => 'Event invitation sent',
            'event_apply' => 'Event participation request submitted',
            
            // Video actions
            'video_upload' => 'Video uploaded',
            'video_update' => 'Video updated',
            'video_delete' => 'Video deleted',
            'video_view' => 'Video viewed',
            'video_like' => 'Video liked',
            'video_comment' => 'Comment added to video',
            
            // User actions
            'profile_update' => 'Profile updated',
            'profile_photo_update' => 'Profile photo updated',
            'banner_update' => 'Banner image updated',
            'password_change' => 'Password changed',
            
            // Admin actions
            'admin_dashboard_access' => 'Admin dashboard accessed',
            'admin_settings_update' => 'System settings updated',
            'admin_user_management' => 'User management action',
            'admin_permission_management' => 'Permission management action',
            
            // Premium actions
            'subscription_create' => 'Premium subscription created',
            'subscription_update' => 'Premium subscription updated',
            'subscription_cancel' => 'Premium subscription cancelled',
            'payment_process' => 'Payment processed',
            
            // Media actions
            'media_upload' => 'Media uploaded',
            'media_delete' => 'Media deleted',
            'media_like' => 'Media liked',
            'media_comment' => 'Comment added to media',
        };

        return $descriptions[$action] ?? "Action performed: {$action}";
    }

    /**
     * Log request data (excluding sensitive information)
     */
    protected function logRequestData(Request $request, array $exclude = []): array
    {
        $defaultExclude = [
            'password',
            'password_confirmation',
            'current_password',
            'new_password',
            'token',
            '_token',
            'csrf_token',
        ];

        $exclude = array_merge($defaultExclude, $exclude);
        
        return $request->except($exclude);
    }

    /**
     * Log validation errors
     */
    protected function logValidationErrors(array $errors, Request $request)
    {
        $this->logError('validation_failed', [
            'errors' => $errors,
            'request_data' => $this->logRequestData($request),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
        ]);
    }

    /**
     * Log database operations
     */
    protected function logDatabaseOperation(string $operation, $model, array $data = [])
    {
        $details = array_merge($data, [
            'model' => get_class($model),
            'model_id' => $model->id ?? null,
        ]);

        $this->logSuccess($operation, $details, get_class($model), $model->id ?? null);
    }

    /**
     * Log file operations
     */
    protected function logFileOperation(string $operation, string $filePath, array $data = [])
    {
        $details = array_merge($data, [
            'file_path' => $filePath,
            'file_size' => file_exists($filePath) ? filesize($filePath) : null,
        ]);

        $this->logSuccess($operation, $details);
    }
} 