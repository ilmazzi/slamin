<?php

// Script per analizzare riferimenti incrociati
require_once 'vendor/autoload.php';

echo "🔍 ANALISI RIFERIMENTI INCROCIATI\n";
echo "=================================\n\n";

// Chiavi problematiche da analizzare
$problematicKeys = [
    'delete', 'view', 'edit', 'cancel', 'title', 'content', 'save', 'close',
    'actions', 'status', 'comments', 'search_placeholder', 'preview'
];

foreach ($problematicKeys as $key) {
    echo "🔑 Analizzando chiave: '$key'\n";
    echo "===============================\n";
    
    // Trova tutti i file che contengono questa chiave
    $files = glob('lang/it/*.php');
    $foundIn = [];
    
    foreach ($files as $file) {
        if (strpos($file, '.backup.') !== false) continue;
        
        $content = file_get_contents($file);
        $fileName = basename($file, '.php');
        
        // Controlla se la chiave esiste
        if (preg_match('/[\'"]([a-zA-Z_][a-zA-Z0-9_]*)[\'"]\s*=>/', $content, $matches)) {
            // Estrae tutte le chiavi del file
            preg_match_all('/[\'"]([a-zA-Z_][a-zA-Z0-9_]*)[\'"]\s*=>/', $content, $allKeys);
            
            if (in_array($key, $allKeys[1])) {
                $foundIn[] = $fileName;
            }
        }
    }
    
    if (count($foundIn) > 1) {
        echo "❌ DUPLICATA in: " . implode(', ', $foundIn) . "\n";
        echo "   File coinvolti: " . count($foundIn) . "\n\n";
    } else {
        echo "✅ OK in: " . implode(', ', $foundIn) . "\n\n";
    }
}

echo "📊 RIEPILOGO PROBLEMI:\n";
echo "======================\n";

// Conta le chiavi più problematiche
$duplicateCounts = [];
$files = glob('lang/it/*.php');
$allKeys = [];

foreach ($files as $file) {
    if (strpos($file, '.backup.') !== false) continue;
    
    $content = file_get_contents($file);
    preg_match_all('/[\'"]([a-zA-Z_][a-zA-Z0-9_]*)[\'"]\s*=>/', $content, $matches);
    
    foreach ($matches[1] as $key) {
        if (!isset($allKeys[$key])) {
            $allKeys[$key] = [];
        }
        $allKeys[$key][] = basename($file, '.php');
    }
}

// Trova le chiavi con più duplicati
foreach ($allKeys as $key => $files) {
    $uniqueFiles = array_unique($files);
    if (count($uniqueFiles) > 1) {
        $duplicateCounts[$key] = count($uniqueFiles);
    }
}

// Ordina per numero di duplicati
arsort($duplicateCounts);

echo "🔥 TOP 20 CHIAVI PIÙ DUPLICATE:\n";
$count = 0;
foreach ($duplicateCounts as $key => $duplicates) {
    if ($count >= 20) break;
    echo sprintf("%2d. %-20s (%d file)\n", ++$count, $key, $duplicates);
}

