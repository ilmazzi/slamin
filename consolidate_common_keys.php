<?php

// Script per consolidare chiavi comuni in modo sicuro
echo "🔧 CONSOLIDAMENTO CHIAVI COMUNI\n";
echo "===============================\n\n";

$langPath = 'lang/it';
$commonFile = $langPath . '/common.php';

// Chiavi universali da consolidare (con le migliori traduzioni)
$universalKeys = [
    'delete' => 'Elimina',
    'view' => 'Visualizza', 
    'edit' => 'Modifica',
    'cancel' => 'Annulla',
    'save' => 'Salva',
    'close' => 'Chiudi',
    'ok' => 'OK',
    'actions' => 'Azioni',
    'status' => 'Stato',
    'preview' => 'Anteprima',
    'search_placeholder' => 'Cerca...',
    'loading' => 'Caricamento...',
    'error' => 'Errore',
    'success' => 'Successo',
    'warning' => 'Attenzione',
    'confirm' => 'Conferma',
    'yes' => 'Sì',
    'no' => 'No',
    'all' => 'Tutti',
    'none' => 'Nessuno',
    'select' => 'Seleziona',
    'filter' => 'Filtra',
    'reset' => 'Reimposta',
    'back' => 'Indietro',
    'next' => 'Avanti',
    'previous' => 'Precedente',
    'first' => 'Primo',
    'last' => 'Ultimo',
    'page' => 'Pagina',
    'of' => 'di',
    'total' => 'Totale',
    'results' => 'Risultati',
    'no_results' => 'Nessun risultato trovato',
    'search' => 'Cerca',
    'clear' => 'Cancella',
    'apply' => 'Applica',
    'refresh' => 'Aggiorna',
    'upload' => 'Carica',
    'download' => 'Scarica',
    'export' => 'Esporta',
    'import' => 'Importa',
    'print' => 'Stampa',
    'copy' => 'Copia',
    'paste' => 'Incolla',
    'cut' => 'Taglia',
    'undo' => 'Annulla',
    'redo' => 'Ripeti'
];

// Leggi il file common.php esistente
$commonContent = '';
if (file_exists($commonFile)) {
    $commonContent = file_get_contents($commonFile);
    echo "📁 File common.php esistente trovato\n";
} else {
    echo "📁 File common.php non trovato, verrà creato\n";
}

// Estrai le chiavi esistenti da common.php
$existingKeys = [];
if (!empty($commonContent)) {
    preg_match_all('/[\'"]([a-zA-Z_][a-zA-Z0-9_]*)[\'"]\s*=>\s*[\'"]([^\'"]*)[\'"]/', $commonContent, $matches);
    for ($i = 0; $i < count($matches[1]); $i++) {
        $existingKeys[$matches[1][$i]] = $matches[2][$i];
    }
}

echo "📊 Chiavi esistenti in common.php: " . count($existingKeys) . "\n";

// Aggiungi le chiavi universali (mantenendo quelle esistenti)
$allCommonKeys = array_merge($existingKeys, $universalKeys);

// Crea il nuovo contenuto per common.php
$newContent = "<?php\n\nreturn [\n";

foreach ($allCommonKeys as $key => $value) {
    $newContent .= "    '$key' => '" . addslashes($value) . "',\n";
}

$newContent .= "];\n";

// Salva il nuovo common.php
file_put_contents($commonFile, $newContent);

echo "✅ Common.php aggiornato con " . count($allCommonKeys) . " chiavi\n";
echo "📋 Chiavi universali aggiunte: " . count($universalKeys) . "\n\n";

echo "🎯 PROSSIMO STEP:\n";
echo "=================\n";
echo "Ora dobbiamo rimuovere le chiavi duplicate dagli altri file\n";
echo "e aggiornare i riferimenti nel codice per usare common.*\n";

