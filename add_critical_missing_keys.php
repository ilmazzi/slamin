<?php

echo "🔧 AGGIUNTA CHIAVI CRITICHE MANCANTI\n";
echo "=====================================\n\n";

// Chiavi critiche da aggiungere per file
$criticalKeys = [
    'home' => [
        'carousel' => [
            'next' => 'Avanti',
            'previous' => 'Indietro'
        ]
    ],
    
    'common' => [
        'are_you_sure' => 'Sei sicuro?',
        'back_to_profile' => 'Torna al profilo',
        'confirm_action' => 'Conferma azione',
        'created_at' => 'Creato il',
        'danger_zone' => 'Zona pericolosa',
        'dark_theme' => 'Tema scuro',
        'help' => 'Aiuto',
        'progress' => 'Progresso',
        'tips' => 'Suggerimenti',
        'unexpected_error' => 'Errore inaspettato'
    ],
    
    'forum' => [
        'all_time' => 'Tutti i tempi',
        'cancel' => 'Annulla',
        'comment_deleted' => 'Commento eliminato',
        'comments' => 'Commenti',
        'confirm_delete_comment' => 'Conferma eliminazione commento',
        'content' => 'Contenuto',
        'created' => 'Creato',
        'delete' => 'Elimina',
        'image' => 'Immagine',
        'login' => 'Accedi',
        'new' => 'Nuovo',
        'no_comments_yet' => 'Nessun commento ancora',
        'post_comment' => 'Pubblica commento',
        'posts' => 'Post',
        'register' => 'Registrati',
        'reply' => 'Rispondi',
        'today' => 'Oggi',
        'title' => 'Titolo',
        'views' => 'Visualizzazioni',
        'write_comment' => 'Scrivi commento'
    ],
    
    'photos' => [
        'confirm_delete' => 'Conferma eliminazione',
        'current_photo' => 'Foto attuale',
        'description' => 'Descrizione',
        'description_placeholder' => 'Descrivi la foto...',
        'max_size' => 'Dimensione massima',
        'save_changes' => 'Salva modifiche',
        'supported_formats' => 'Formati supportati',
        'title' => 'Titolo',
        'title_placeholder' => 'Inserisci il titolo...',
        'upload_error' => 'Errore caricamento',
        'upload_photo' => 'Carica foto'
    ],
    
    'videos' => [
        'current_videos' => 'Video attuali',
        'description' => 'Descrizione',
        'gallery' => 'Galleria',
        'loading' => 'Caricamento',
        'loading_video' => 'Caricamento video...',
        'manage_videos' => 'Gestisci video',
        'max_size' => 'Dimensione massima',
        'my_videos' => 'I miei video',
        'private' => 'Privato',
        'public' => 'Pubblico',
        'select_file' => 'Seleziona file',
        'select_thumbnail' => 'Seleziona thumbnail',
        'send' => 'Invia',
        'supported_formats' => 'Formati supportati',
        'tags' => 'Tag',
        'tags_help' => 'Aiuto tag',
        'tags_placeholder' => 'Inserisci tag...',
        'thumbnail' => 'Thumbnail',
        'thumbnail_help' => 'Aiuto thumbnail',
        'title' => 'Titolo',
        'upload_error' => 'Errore caricamento',
        'upload_new_video' => 'Carica nuovo video',
        'upload_video' => 'Carica video',
        'view_gallery' => 'Vedi galleria',
        'view_my_videos' => 'Vedi i miei video',
        'view_all_videos' => 'Vedi tutti i video',
        'video_limit' => 'Limite video',
        'views' => 'Visualizzazioni'
    ]
];

$totalAdded = 0;

foreach ($criticalKeys as $file => $keys) {
    $filePath = "lang/it/$file.php";
    
    echo "📄 Aggiornando: $file.php\n";
    
    if (!file_exists($filePath)) {
        echo "   ❌ File $filePath non trovato\n";
        continue;
    }
    
    $content = file_get_contents($filePath);
    
    // Rimuove la parentesi graffa di chiusura
    $content = rtrim($content);
    $content = rtrim($content, '];');
    
    $added = 0;
    
    foreach ($keys as $key => $value) {
        if (is_array($value)) {
            // Chiave annidata
            if (strpos($content, "'$key'") === false) {
                $content .= "\n    '$key' => [";
                foreach ($value as $subKey => $subValue) {
                    $content .= "\n        '$subKey' => '$subValue',";
                }
                $content .= "\n    ],";
                $added += count($value);
                echo "   ✅ Aggiunta sezione: $key (" . count($value) . " chiavi)\n";
            }
        } else {
            // Chiave semplice
            if (strpos($content, "'$key'") === false && strpos($content, "\"$key\"") === false) {
                $content .= "\n    '$key' => '$value',";
                $added++;
                echo "   ✅ Aggiunta: $key\n";
            }
        }
    }
    
    if ($added > 0) {
        $content .= "\n\n];";
        file_put_contents($filePath, $content);
        echo "   📝 Salvato con $added nuove chiavi\n";
        $totalAdded += $added;
    } else {
        echo "   ℹ️  Nessuna nuova chiave aggiunta\n";
    }
    
    echo "\n";
}

echo "📊 RIEPILOGO:\n";
echo "=============\n";
echo "Chiavi aggiunte: $totalAdded\n";
echo "File aggiornati: " . count($criticalKeys) . "\n\n";

echo "🎯 PROSSIMI PASSI:\n";
echo "==================\n";
echo "1. Testare le pagine aggiornate\n";
echo "2. Continuare con gli altri file\n";
echo "3. Verificare che tutte le traduzioni funzionino\n";

