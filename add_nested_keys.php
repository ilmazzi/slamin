<?php
echo "🔧 AGGIUNTA CHIAVI ANNIDATE MANCANTI\n";
echo "====================================\n\n";

// Chiavi annidate per home.php
$homeNestedKeys = [
    'carousel' => [
        'next' => 'Avanti',
        'previous' => 'Indietro'
    ],
    'stats' => [
        'total_events' => 'Eventi Totali',
        'total_users' => 'Utenti Totali',
        'total_videos' => 'Video Totali',
        'total_views' => 'Visualizzazioni Totali'
    ]
];

// Chiavi annidate per groups.php
$groupsNestedKeys = [
    'tips' => [
        'create_group' => 'Crea un gruppo per riunire persone con interessi comuni',
        'group_events' => 'Organizza eventi esclusivi per i membri del gruppo',
        'invite_members' => 'Invita utenti a unirsi al tuo gruppo',
        'manage_permissions' => 'Gestisci i permessi e i ruoli dei membri',
        'private_visibility' => 'I gruppi privati sono visibili solo ai membri',
        'public_visibility' => 'I gruppi pubblici sono visibili a tutti'
    ]
];

// Chiavi annidate per admin_general.php
$adminGeneralNestedKeys = [
    'help' => [
        'deleted_successfully' => 'Aiuto eliminato con successo',
        'updated_successfully' => 'Aiuto aggiornato con successo'
    ],
    'translations' => [
        'sync_all_success' => 'Tutte le traduzioni sincronizzate con successo'
    ]
];

// Chiavi semplici per admin_general.php
$adminGeneralSimpleKeys = [
    'translations_synced_error' => 'Errore sincronizzazione traduzioni',
    'translations_synced_to_file_error' => 'Errore salvataggio traduzioni su file',
    'translations_synced_to_file_success' => 'Traduzioni salvate su file con successo',
    'user_deleted_successfully' => 'Utente eliminato con successo',
    'user_updated_successfully' => 'Utente aggiornato con successo'
];

// Chiavi per articles.php
$articlesSimpleKeys = [
    'my_articles' => 'I miei articoli',
    'news' => 'Notizie',
    'no_bio' => 'Nessuna biografia',
    'no_search_results' => 'Nessun risultato di ricerca',
    'not_published' => 'Non pubblicato',
    'open_new_tab' => 'Apri in una nuova scheda',
    'pending_review' => 'In attesa di revisione',
    'publish_error' => 'Errore pubblicazione',
    'read_time' => 'Tempo di lettura',
    'related_articles' => 'Articoli correlati',
    'report_details' => 'Dettagli segnalazione',
    'report_reason' => 'Motivo segnalazione',
    'report_status' => 'Stato segnalazione',
    'reported_at' => 'Segnalato il',
    'reported_by' => 'Segnalato da',
    'reports_management' => 'Gestione segnalazioni',
    'review_report' => 'Revisiona segnalazione',
    'search_articles' => 'Cerca articoli',
    'select_status' => 'Seleziona stato',
    'show_all' => 'Mostra tutti'
];

$files = [
    'home' => ['nested' => $homeNestedKeys, 'simple' => []],
    'groups' => ['nested' => $groupsNestedKeys, 'simple' => []],
    'admin_general' => ['nested' => $adminGeneralNestedKeys, 'simple' => $adminGeneralSimpleKeys],
    'articles' => ['nested' => [], 'simple' => $articlesSimpleKeys]
];

$totalAdded = 0;

foreach ($files as $file => $data) {
    $filePath = "lang/it/$file.php";
    
    if (!file_exists($filePath)) {
        echo "❌ File $filePath non trovato\n";
        continue;
    }
    
    echo "📄 Aggiornando: $file.php\n";
    
    $content = file_get_contents($filePath);
    $content = rtrim($content);
    $content = rtrim($content, '];');
    
    $added = 0;
    
    // Aggiungi chiavi annidate
    foreach ($data['nested'] as $parentKey => $subKeys) {
        if (strpos($content, "'$parentKey'") === false) {
            $content .= "\n    '$parentKey' => [";
            foreach ($subKeys as $subKey => $value) {
                $content .= "\n        '$subKey' => '$value',";
            }
            $content .= "\n    ],";
            $added += count($subKeys);
            echo "   ✅ Aggiunta sezione annidata: $parentKey (" . count($subKeys) . " chiavi)\n";
        }
    }
    
    // Aggiungi chiavi semplici
    foreach ($data['simple'] as $key => $value) {
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

