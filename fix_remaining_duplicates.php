<?php

echo "🔧 RISOLUZIONE CHIAVI DUPLICATE RIMANENTI\n";
echo "=========================================\n\n";

$langPath = 'lang/it';

// Chiavi da rinominare per contesto
$renameRules = [
    // Title in contesti diversi
    'articles' => ['title' => 'article_title'],
    'events_general' => ['title' => 'event_title'],
    'photos' => ['title' => 'photo_title'],
    'videos' => ['title' => 'video_title'],
    'forum' => ['title' => 'post_title'],
    
    // Content in contesti diversi
    'articles' => ['content' => 'article_content'],
    'forum' => ['content' => 'post_content'],
    'profile' => ['content' => 'profile_content'],
    
    // Comments in contesti diversi
    'articles' => ['comments' => 'article_comments'],
    'forum' => ['comments' => 'post_comments'],
    'media' => ['comments' => 'media_comments'],
    'poems' => ['comments' => 'poem_comments'],
    
    // Description in contesti diversi
    'articles' => ['description' => 'article_description'],
    'events_general' => ['description' => 'event_description'],
    'groups' => ['description' => 'group_description'],
    'photos' => ['description' => 'photo_description'],
    'videos' => ['description' => 'video_description'],
    
    // Name in contesti diversi
    'groups' => ['name' => 'group_name'],
    'permissions' => ['name' => 'permission_name'],
    
    // Status in contesti diversi
    'articles' => ['status' => 'article_status'],
    'events_general' => ['status' => 'event_status'],
    'gamification' => ['status' => 'badge_status'],
    'gigs' => ['status' => 'gig_status'],
    'invitations' => ['status' => 'invitation_status'],
    'permissions' => ['status' => 'permission_status'],
    'poems' => ['status' => 'poem_status'],
    'translations' => ['status' => 'translation_status'],
];

$totalRenamed = 0;

foreach ($renameRules as $file => $rules) {
    $filePath = $langPath . '/' . $file . '.php';
    
    if (!file_exists($filePath)) {
        echo "⚠️  File $file.php non trovato\n";
        continue;
    }
    
    echo "🔧 Processando: $file.php\n";
    
    $content = file_get_contents($filePath);
    $originalContent = $content;
    $renamedCount = 0;
    
    foreach ($rules as $oldKey => $newKey) {
        // Pattern per trovare e sostituire la chiave
        $pattern = "/([\'\"])" . preg_quote($oldKey, '/') . "([\'\"])/";
        $replacement = '$1' . $newKey . '$2';
        
        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, $replacement, $content);
            $renamedCount++;
            echo "   ✅ '$oldKey' → '$newKey'\n";
        }
    }
    
    if ($content !== $originalContent) {
        file_put_contents($filePath, $content);
        echo "   📝 Salvato con $renamedCount modifiche\n";
        $totalRenamed += $renamedCount;
    } else {
        echo "   ℹ️  Nessuna modifica necessaria\n";
    }
    
    echo "\n";
}

echo "📊 RIEPILOGO:\n";
echo "=============\n";
echo "Chiavi rinominate: $totalRenamed\n";
echo "File processati: " . count($renameRules) . "\n\n";

echo "🎯 PROSSIMO STEP:\n";
echo "=================\n";
echo "Ora dobbiamo aggiornare i riferimenti nel codice\n";
echo "da 'articles.title' a 'articles.article_title' ecc.\n";

