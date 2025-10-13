<?php

echo "🔧 AGGIUNTA CHIAVI PROFILE, POEMS, GIGS\n";
echo "=======================================\n\n";

// Chiavi per profile.php
$profileKeys = [
    'articles' => 'Articoli',
    'cancel' => 'Annulla',
    'city' => 'Città',
    'city_placeholder' => 'Inserisci la città...',
    'commented_content' => 'Contenuti commentati',
    'content' => 'Contenuto',
    'create_event' => 'Crea evento',
    'create_first_event' => 'Crea il tuo primo evento',
    'created_content' => 'Contenuti creati',
    'email' => 'Email',
    'export' => 'Esporta',
    'follow' => 'Segui',
    'followers' => 'Follower',
    'following' => 'Seguiti',
    'full_name' => 'Nome completo',
    'liked_content' => 'Contenuti piaciuti',
    'location' => 'Posizione',
    'manage_videos' => 'Gestisci video',
    'member_since' => 'Membro dal',
    'nickname' => 'Nickname',
    'no_poems_description' => 'Nessuna poesia trovata',
    'no_recent_activity' => 'Nessuna attività recente',
    'photos' => 'Foto',
    'posts' => 'Post',
    'quick_actions' => 'Azioni rapide',
    'recent_activity' => 'Attività recente',
    'save' => 'Salva',
    'settings' => 'Impostazioni',
    'statistics' => 'Statistiche',
    'unfollow' => 'Smetti di seguire',
    'uploaded_content' => 'Contenuti caricati',
    'venues' => 'Venue',
    'videos' => 'Video',
    'view' => 'Visualizza',
    'view_all_activity' => 'Vedi tutta l\'attività',
    'view_all_poems' => 'Vedi tutte le poesie',
    'view_my_videos' => 'Vedi i miei video',
    'views' => 'Visualizzazioni',
    'upload_new_video' => 'Carica nuovo video'
];

// Chiavi per poems.php (solo le più importanti)
$poemsKeys = [
    'about_author' => 'Informazioni autore',
    'available_languages' => 'Lingue disponibili',
    'bookmarks' => [
        'add_bookmark' => 'Aggiungi ai preferiti',
        'remove_bookmark' => 'Rimuovi dai preferiti'
    ],
    'cancel' => 'Annulla',
    'categories' => 'Categorie',
    'category' => 'Categoria',
    'content' => 'Contenuto',
    'create_poem' => 'Crea poesia',
    'delete' => 'Elimina',
    'description' => 'Descrizione',
    'edit' => 'Modifica',
    'language' => 'Lingua',
    'like' => 'Mi piace',
    'likes' => 'Mi piace',
    'no_poems_found' => 'Nessuna poesia trovata',
    'poems' => 'Poesie',
    'published' => 'Pubblicato',
    'save' => 'Salva',
    'search' => 'Cerca',
    'title' => 'Titolo',
    'unlike' => 'Non mi piace più',
    'view' => 'Visualizza'
];

// Chiavi per gigs.php (solo le più importanti)
$gigsKeys = [
    'about_author' => 'Informazioni autore',
    'actions' => [
        'apply' => 'Candidati',
        'close_gig' => 'Chiudi gig',
        'confirm_close' => 'Conferma chiusura',
        'confirm_close_text' => 'Sei sicuro di voler chiudere questo gig?',
        'confirm_reopen' => 'Conferma riapertura',
        'confirm_reopen_text' => 'Sei sicuro di voler riaprire questo gig?',
        'message' => 'Messaggio',
        'message_placeholder' => 'Scrivi il tuo messaggio...',
        'read' => 'Leggi',
        'reopen' => 'Riapri'
    ],
    'applications' => 'Candidature',
    'cancel' => 'Annulla',
    'close' => 'Chiudi',
    'confirm_delete' => 'Conferma eliminazione',
    'create_gig' => 'Crea gig',
    'delete' => 'Elimina',
    'description' => 'Descrizione',
    'edit' => 'Modifica',
    'gigs' => 'Gig',
    'no_gigs_found' => 'Nessun gig trovato',
    'save' => 'Salva',
    'status' => 'Stato',
    'title' => 'Titolo',
    'view' => 'Visualizza'
];

$files = [
    'profile' => $profileKeys,
    'poems' => $poemsKeys,
    'gigs' => $gigsKeys
];

$totalAdded = 0;

foreach ($files as $file => $keys) {
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
echo "File aggiornati: " . count($files) . "\n\n";

echo "🎯 PROSSIMI PASSI:\n";
echo "==================\n";
echo "1. Testare le pagine aggiornate\n";
echo "2. Continuare con gli altri file\n";
echo "3. Verificare che tutte le traduzioni funzionino\n";

