<?php

// Script per rimuovere duplicati e aggiornare riferimenti
echo "🔄 RIMOZIONE DUPLICATI E AGGIORNAMENTO RIFERIMENTI\n";
echo "=================================================\n\n";

$langPath = 'lang/it';

// Chiavi che ora sono in common.php
$commonKeys = [
    'delete', 'view', 'edit', 'cancel', 'save', 'close', 'ok',
    'actions', 'status', 'preview', 'search_placeholder', 'loading',
    'error', 'success', 'warning', 'confirm', 'yes', 'no', 'all',
    'none', 'select', 'filter', 'reset', 'back', 'next', 'previous',
    'first', 'last', 'page', 'of', 'total', 'results', 'no_results',
    'search', 'clear', 'apply', 'refresh', 'upload', 'download',
    'export', 'import', 'print', 'copy', 'paste', 'cut', 'undo', 'redo'
];

// File da processare (esclusi common.php e file di sistema)
$filesToProcess = glob($langPath . '/*.php');
$filesToProcess = array_filter($filesToProcess, function($file) {
    $fileName = basename($file, '.php');
    return $fileName !== 'common' && strpos($file, '.backup.') === false;
});

echo "📁 File da processare: " . count($filesToProcess) . "\n\n";

$totalRemoved = 0;

foreach ($filesToProcess as $file) {
    $fileName = basename($file, '.php');
    echo "🔧 Processando: $fileName.php\n";
    
    $content = file_get_contents($file);
    $originalContent = $content;
    
    $removedCount = 0;
    
    // Rimuovi le chiavi duplicate che sono ora in common.php
    foreach ($commonKeys as $key) {
        // Pattern per trovare la chiave e rimuoverla
        $pattern = "/[\'"]({$key})[\'"]\s*=>\s*[\'"]([^\'"]*)[\'"],?\s*\n/";
        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, '', $content);
            $removedCount++;
        }
    }
    
    // Pulisci righe vuote multiple
    $content = preg_replace('/\n\s*\n\s*\n/', "\n\n", $content);
    
    // Salva il file aggiornato
    if ($content !== $originalContent) {
        file_put_contents($file, $content);
        echo "   ✅ Rimosse $removedCount chiavi duplicate\n";
        $totalRemoved += $removedCount;
    } else {
        echo "   ℹ️  Nessuna modifica necessaria\n";
    }
    
    echo "\n";
}

echo "📊 RIEPILOGO:\n";
echo "=============\n";
echo "Chiavi duplicate rimosse: $totalRemoved\n";
echo "File processati: " . count($filesToProcess) . "\n\n";

echo "🎯 PROSSIMO STEP:\n";
echo "=================\n";
echo "Ora dobbiamo aggiornare i riferimenti nel codice PHP/Blade\n";
echo "da 'file.key' a 'common.key' per le chiavi consolidate\n";

