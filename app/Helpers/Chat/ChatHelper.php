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
}

