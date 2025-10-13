<?php

// Script per analizzare le traduzioni
require_once 'vendor/autoload.php';

$langPath = 'lang/it';
$allKeys = [];
$duplicates = [];
$fileKeys = [];

echo "🔍 ANALISI FILE DI TRADUZIONE\n";
echo "============================\n\n";

// Scansiona tutti i file PHP (esclusi backup)
$files = glob($langPath . '/*.php');
foreach ($files as $file) {
    if (strpos($file, '.backup.') !== false) continue;
    
    $fileName = basename($file, '.php');
    echo "📁 Analizzando: $fileName.php\n";
    
    $keys = [];
    $content = file_get_contents($file);
    
    // Estrae le chiavi usando regex
    preg_match_all('/[\'"]([a-zA-Z_][a-zA-Z0-9_]*)[\'"]\s*=>/', $content, $matches);
    
    if (!empty($matches[1])) {
        $keys = $matches[1];
        $fileKeys[$fileName] = $keys;
        
        echo "   Chiavi trovate: " . count($keys) . "\n";
        
        // Controlla duplicati
        foreach ($keys as $key) {
            if (isset($allKeys[$key])) {
                $duplicates[$key][] = $allKeys[$key];
                $duplicates[$key][] = $fileName;
            } else {
                $allKeys[$key] = $fileName;
            }
        }
    }
}

echo "\n📊 STATISTICHE:\n";
echo "===============\n";
echo "File analizzati: " . count($fileKeys) . "\n";
echo "Chiavi totali: " . count($allKeys) . "\n";
echo "Chiavi duplicate: " . count($duplicates) . "\n";

if (!empty($duplicates)) {
    echo "\n🚨 CHIAVI DUPLICATE TROVATE:\n";
    echo "============================\n";
    foreach ($duplicates as $key => $files) {
        echo "Key: '$key' in files: " . implode(', ', array_unique($files)) . "\n";
    }
}

echo "\n📋 RIEPILOGO PER FILE:\n";
echo "=====================\n";
foreach ($fileKeys as $file => $keys) {
    echo "$file.php: " . count($keys) . " chiavi\n";
}

