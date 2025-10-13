<?php

echo "🔧 AGGIUNTA CHIAVI RIMANENTI CRITICHE\n";
echo "=====================================\n\n";

// Chiavi per admin_general.php (le più importanti)
$adminGeneralKeys = [
    'actions' => 'Azioni',
    'add_first_language' => 'Aggiungi prima lingua',
    'add_language' => 'Aggiungi lingua',
    'all_statuses' => 'Tutti gli stati',
    'bulk_assignment_completed' => 'Assegnazione bulk completata',
    'cache_clear_error' => 'Errore pulizia cache',
    'cache_cleared_error' => 'Errore cache pulita',
    'cache_cleared_success' => 'Cache pulita con successo',
    'cancel' => 'Annulla',
    'cannot_delete_last_admin' => 'Impossibile eliminare ultimo admin',
    'clear_cache' => 'Pulisci cache',
    'clear_cache_confirm' => 'Conferma pulizia cache',
    'close' => 'Chiudi',
    'confirm_delete' => 'Conferma eliminazione',
    'create_error' => 'Errore creazione',
    'delete' => 'Elimina',
    'delete_error' => 'Errore eliminazione',
    'edit' => 'Modifica',
    'edit_translation' => 'Modifica traduzione',
    'error' => 'Errore',
    'filter' => 'Filtra',
    'language' => 'Lingua',
    'manage' => 'Gestisci',
    'no_languages_description' => 'Nessuna lingua disponibile',
    'preview' => 'Anteprima',
    'progress' => 'Progresso',
    'refresh' => 'Aggiorna',
    'reset' => 'Reset',
    'search' => 'Cerca',
    'search_placeholder' => 'Cerca...',
    'select_group' => 'Seleziona gruppo',
    'showing' => 'Mostrando',
    'status' => 'Stato',
    'success' => 'Successo',
    'title' => 'Titolo',
    'update' => 'Aggiorna',
    'update_error' => 'Errore aggiornamento',
    'updated_successfully' => 'Aggiornato con successo',
    'view' => 'Visualizza'
];

// Chiavi per articles.php (le più importanti)
$articlesKeys = [
    'actions' => 'Azioni',
    'all_statuses' => 'Tutti gli stati',
    'article_stats' => 'Statistiche articoli',
    'articles' => 'Articoli',
    'back_to_dashboard' => 'Torna alla dashboard',
    'browse_all_articles' => 'Sfoglia tutti gli articoli',
    'cancel' => 'Annulla',
    'categories' => 'Categorie',
    'category' => 'Categoria',
    'close' => 'Chiudi',
    'confirm_delete' => 'Conferma eliminazione',
    'confirm_mark_as' => 'Conferma segna come',
    'content' => 'Contenuto',
    'copyright' => 'Copyright',
    'create_error' => 'Errore creazione',
    'delete' => 'Elimina',
    'delete_error' => 'Errore eliminazione',
    'details' => 'Dettagli',
    'edit' => 'Modifica',
    'error_loading_report' => 'Errore caricamento report',
    'image_help' => 'Aiuto immagine',
    'max_size' => 'Dimensione massima',
    'meta_keywords_help' => 'Aiuto meta keywords',
    'name' => 'Nome',
    'no_details_provided' => 'Nessun dettaglio fornito',
    'no_reports' => 'Nessun report',
    'no_reports_description' => 'Nessun report disponibile',
    'other' => 'Altro',
    'pending' => 'In sospeso',
    'preview' => 'Anteprima',
    'published' => 'Pubblicato',
    'report_marked_as' => 'Report segnato come',
    'review_error' => 'Errore revisione',
    'save' => 'Salva',
    'save_draft' => 'Salva bozza',
    'select_category' => 'Seleziona categoria',
    'status' => 'Stato',
    'success' => 'Successo',
    'tags' => 'Tag',
    'tags_help' => 'Aiuto tag',
    'title' => 'Titolo',
    'update' => 'Aggiorna',
    'update_article' => 'Aggiorna articolo',
    'update_error' => 'Errore aggiornamento',
    'updated_successfully' => 'Aggiornato con successo',
    'view' => 'Visualizza'
];

// Chiavi per register.php
$registerKeys = [
    'already_have_account' => 'Hai già un account?',
    'complete_ecosystem' => 'Ecosistema completo',
    'email' => 'Email',
    'email_placeholder' => 'La tua email',
    'events_general' => 'Eventi',
    'fast_registration' => 'Registrazione veloce',
    'flexible_roles' => 'Ruoli flessibili',
    'four_main_roles' => 'Quattro ruoli principali',
    'full_name' => 'Nome completo',
    'login' => 'Accedi',
    'nickname' => 'Nickname',
    'nickname_placeholder' => 'Il tuo nickname pubblico',
    'optional' => 'Opzionale',
    'password' => 'Password',
    'register' => 'Registrati',
    'venues' => 'Venue',
    'why_join_slam_in' => 'Perché unirsi a Slam In?'
];

$files = [
    'admin_general' => $adminGeneralKeys,
    'articles' => $articlesKeys,
    'register' => $registerKeys
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
        if (strpos($content, "'$key'") === false && strpos($content, "\"$key\"") === false) {
            $content .= "\n    '$key' => '$value',";
            $added++;
            echo "   ✅ Aggiunta: $key\n";
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

