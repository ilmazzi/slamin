<?php

namespace App\Helpers\Chat;

class ChatHelper
{
    /**
     * Format table name with prefix
     */
    public static function formatTableName(string $name): string
    {
        return config('chat.table_prefix') . $name;
    }

    /**
     * Get storage disk
     */
    public static function storageDisk(): string
    {
        return config('chat.storage.disk', 'public');
    }

    /**
     * Get storage folder
     */
    public static function storageFolder(): string
    {
        return config('chat.storage.folder', 'chat');
    }

    /**
     * Get index route name
     */
    public static function indexRouteName(): string
    {
        return config('chat.routes.index_name', 'chat.index');
    }

    /**
     * Get view route name
     */
    public static function viewRouteName(): string
    {
        return config('chat.routes.view_name', 'chat.show');
    }

    /**
     * Get max group members
     */
    public static function maxGroupMembers(): int
    {
        return config('chat.features.max_group_members', 256);
    }

    /**
     * Check if using UUID
     */
    public static function usesUuid(): bool
    {
        return config('chat.features.use_uuid', false);
    }

    /**
     * Get searchable fields
     */
    public static function searchableFields(): array
    {
        return config('chat.searchable_fields', ['name', 'email']);
    }

    /**
     * Check if broadcasting is enabled
     */
    public static function broadcastingEnabled(): bool
    {
        return config('chat.broadcasting.enabled', true);
    }

    /**
     * Show new chat modal button
     */
    public static function showNewChatModalButton(): bool
    {
        return true;
    }

    /**
     * Allow chats search
     */
    public static function allowChatsSearch(): bool
    {
        return true;
    }

    /**
     * Show new group modal button
     */
    public static function showNewGroupModalButton(): bool
    {
        return true;
    }

    /**
     * Check if notifications are enabled
     */
    public static function notificationsEnabled(): bool
    {
        return true;
    }

    /**
     * Get primary color
     */
    public static function getColor(): string
    {
        return '#3B82F6'; // Blue color
    }

    /**
     * Encode morph class for notifications
     */
    public static function encodeMorphClass(string $morphClass): string
    {
        // Simple encoding - in a real implementation you might want more sophisticated encoding
        return base64_encode($morphClass);
    }

    /**
     * Get formatted media MIME types for accept attribute
     */
    public static function formattedMediaMimesForAcceptAttribute(): string
    {
        $mimes = config('wirechat.attachments.media_mimes', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        return '.' . implode(',.', $mimes);
    }

    /**
     * Get formatted file MIME types for accept attribute
     */
    public static function formattedFileMimesForAcceptAttribute(): string
    {
        $mimes = config('wirechat.attachments.file_mimes', ['pdf', 'doc', 'docx', 'txt', 'zip']);
        return '.' . implode(',.', $mimes);
    }

}

