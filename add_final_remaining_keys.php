<?php
echo "🔧 AGGIUNTA FINALE CHIAVI RIMANENTI\n";
echo "===================================\n\n";

// Chiavi per articles.php
$articlesKeys = [
    'published_successfully' => 'Pubblicato con successo',
    'read_more' => 'Leggi di più',
    'refresh' => 'Aggiorna',
    'remove_image' => 'Rimuovi immagine',
    'search' => 'Cerca',
    'search_placeholder' => 'Cerca articoli...',
    'search_results' => 'Risultati ricerca',
    'share_on_facebook' => 'Condividi su Facebook',
    'share_on_twitter' => 'Condividi su Twitter',
    'share_on_linkedin' => 'Condividi su LinkedIn',
    'unpublish' => 'Annulla pubblicazione',
    'unpublished_successfully' => 'Pubblicazione annullata con successo',
    'updated_at' => 'Aggiornato il',
    'upload_image' => 'Carica immagine',
    'view_article' => 'Visualizza articolo',
    'view_count' => 'Conteggio visualizzazioni',
    'views_count' => 'Numero visualizzazioni',
    'write_article' => 'Scrivi articolo',
    'your_articles' => 'I tuoi articoli',
    'report_details' => 'Dettagli segnalazione',
    'reported_at' => 'Segnalato il'
];

$files = [
    'articles' => $articlesKeys
];

$totalAdded = 0;

foreach ($files as $file => $keys) {
    $filePath = "lang/it/$file.php";
    
    if (!file_exists($filePath)) {
        echo "❌ File $filePath non trovato\n";
        continue;
    }
    
    echo "📄 Aggiornando: $file.php (" . count($keys) . " chiavi)\n";
    
    $content = file_get_contents($filePath);
    $content = rtrim($content);
    $content = rtrim($content, '];');
    
    $added = 0;
    
    foreach ($keys as $key => $value) {
        if (strpos($content, "'$key'") === false && strpos($content, "\"$key\"") === false) {
            $content .= "\n    '$key' => '$value',";
            $added++;
        }
    }
    
    if ($added > 0) {
        $content .= "\n\n];";
        file_put_contents($filePath, $content);
        echo "   ✅ Aggiunte $added chiavi\n";
        $totalAdded += $added;
    } else {
        echo "   ℹ️  Nessuna nuova chiave aggiunta\n";
    }
}

echo "\n📊 RIEPILOGO:\n";
echo "=============\n";
echo "Chiavi aggiunte: $totalAdded\n\n";

