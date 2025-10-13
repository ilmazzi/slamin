<?php

echo "🔥 ELIMINAZIONE TOTALE CHIAVI DUPLICATE\n";
echo "======================================\n\n";

$langPath = 'lang/it';

// Chiavi che devono essere SOLO in common.php (stesso significato)
$commonOnlyKeys = [
    // Azioni universali
    'update', 'confirm_delete', 'pending', 'created_at', 'all_statuses',
    
    // Contenuti universali  
    'comments', 'content', 'description', 'language', 'translation_info',
    
    // Elementi universali
    'name', 'title', 'settings', 'filter', 'search', 'loading', 'error', 'success',
    'warning', 'confirm', 'yes', 'no', 'all', 'none', 'select', 'reset', 'back',
    'next', 'previous', 'first', 'last', 'page', 'of', 'total', 'results',
    'no_results', 'clear', 'apply', 'refresh', 'upload', 'download', 'export',
    'import', 'print', 'copy', 'paste', 'cut', 'undo', 'redo', 'home', 'dashboard',
    'manage', 'view_details', 'copyright', 'back_to_dashboard', 'snap', 'articles',
    'news', 'italian', 'english', 'french', 'german', 'spanish', 'portuguese',
    'email', 'author', 'poem_not_available', 'article_not_available',
    'pinned_announcement', 'accept_request', 'reject_request', 'invite_artists',
    'accept', 'reject', 'full_name', 'avatar', 'current_thumbnail', 'photo',
    'credit_card', 'paypal', 'notifications', 'slam_in', 'slam_in_logo',
    'three_dots_separator', 'new_content', 'popular_content', 'statistics',
    'quick_actions', 'help', 'faq', 'view_all', 'events', 'past_events',
    'private', 'category', 'current_image', 'image_help', 'location', 'free',
    'select_category', 'online', 'progress', 'pending_invitations', 'select_group',
    'users', 'tags', 'tags_help', 'tags_placeholder', 'not_specified',
    'save_draft', 'not_available', 'other', 'currency', 'all_groups',
    'user_not_found', 'role_audience', 'media_section', 'gigs',
    'create_event_button', 'published', 'draft', 'participants', 'reply',
    'comment_deleted', 'new', 'today', 'views', 'created', 'login', 'register',
    'add_participant', 'max_participants', 'score', 'judge', 'no_participants',
    'add_round', 'categories', 'management', 'breadcrumb', 'organizer_section',
    'groups', 'admin', 'create_group', 'group_invitations', 'decline', 'remove',
    'image', 'role', 'left_group', 'member_removed', 'invitation_sent',
    'filter_all', 'filter_public', 'filter_private', 'search_users_placeholder',
    'search_results', 'invited_users', 'stats', 'pending_requests', 'showing',
    'send_invitation', 'status_pending', 'group_info', 'tips', 'status_column',
    'no_message', 'expired', 'no_content', 'no_content_description', 'read_more',
    'upcoming_events', 'details', 'following', 'editorial', 'likes', 'timestamp',
    'add_language', 'add_first_language', 'language_code', 'language_code_help',
    'level', 'updated_successfully', 'deleted_successfully', 'no_languages_description',
    'password', 'remember_me', 'register_here', 'video', 'interactions',
    'write_comment', 'post_comment', 'no_comments_yet', 'confirm_delete_comment',
    'permissions', 'manage_users', 'export', 'search_users', 'photos',
    'upload_photo', 'current_photo', 'max_size', 'upload_error', 'title_placeholder',
    'description_placeholder', 'save_changes', 'poems', 'fields', 'liked',
    'filters', 'languages', 'delete_confirm', 'placeholders', 'about_author',
    'no_bio', 'member_since', 'terms_accepted', 'days_remaining', 'most_popular',
    'view_all_poems', 'no_poems_description', 'create_event', 'create_first_event',
    'organized_events', 'recent_activity', 'view_all_activity', 'no_recent_activity',
    'follow', 'send_message', 'nickname', 'city', 'city_placeholder', 'country',
    'videos', 'manage_notifications', 'followers', 'posts', 'nickname_placeholder',
    'email_placeholder', 'flexible_roles', 'optional', 'four_main_roles', 'roles',
    'venues', 'platform_description', 'already_have_account', 'why_join_slam_in',
    'fast_registration', 'complete_ecosystem', 'role_judge_description',
    'role_technician_description', 'no_results_found', 'search_error', 'system_logs',
    'chat', 'create_translation', 'edit_translation', 'messages',
    'no_translations_description', 'subtitle', 'all_time', 'drafts', 'role_member',
    'upload_video', 'my_videos', 'upload_new_video', 'supported_formats',
    'thumbnail', 'send', 'thumbnail_help', 'select_thumbnail', 'public',
    'manage_videos', 'view_my_videos', 'video_limit', 'loading_video', 'select_file',
    'my_wishlist'
];

// File da processare (escluso common.php)
$files = glob($langPath . '/*.php');
$files = array_filter($files, function($file) {
    return basename($file, '.php') !== 'common';
});

echo "📁 File da processare: " . count($files) . "\n";
echo "🔑 Chiavi da rimuovere: " . count($commonOnlyKeys) . "\n\n";

$totalRemoved = 0;

foreach ($files as $file) {
    $fileName = basename($file, '.php');
    echo "🔧 Processando: $fileName.php\n";
    
    $content = file_get_contents($file);
    $originalLines = explode("\n", $content);
    $newLines = [];
    $removedCount = 0;
    
    foreach ($originalLines as $line) {
        $shouldKeep = true;
        
        foreach ($commonOnlyKeys as $key) {
            // Controlla se la riga contiene una chiave da rimuovere
            if (preg_match('/^\s*[\'"](.*)[\'"]\s*=>/', $line, $matches)) {
                if ($matches[1] === $key) {
                    $shouldKeep = false;
                    $removedCount++;
                    break;
                }
            }
        }
        
        if ($shouldKeep) {
            $newLines[] = $line;
        }
    }
    
    if ($removedCount > 0) {
        $newContent = implode("\n", $newLines);
        file_put_contents($file, $newContent);
        echo "   ✅ Rimosse $removedCount chiavi duplicate\n";
        $totalRemoved += $removedCount;
    } else {
        echo "   ℹ️  Nessuna modifica necessaria\n";
    }
    
    echo "\n";
}

echo "📊 RIEPILOGO FINALE:\n";
echo "=====================\n";
echo "Chiavi duplicate rimosse: $totalRemoved\n";
echo "File processati: " . count($files) . "\n\n";

echo "🎯 RISULTATO ATTESO:\n";
echo "====================\n";
echo "Ora TUTTE le chiavi duplicate dovrebbero essere eliminate!\n";
echo "Le chiavi universali sono solo in common.php\n";

