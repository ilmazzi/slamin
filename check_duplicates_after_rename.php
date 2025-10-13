<?php

echo "🔍 CONTROLLO DUPLICATI DOPO RINOMINA\n";
echo "====================================\n\n";

$langPath = 'lang/it';
$allKeys = [];
$duplicates = [];

// Analizza tutti i file
$files = glob($langPath . '/*.php');
foreach ($files as $file) {
    $fileName = basename($file, '.php');
    $content = file_get_contents($file);
    
    // Estrae le chiavi
    preg_match_all('/[\'"]([a-zA-Z_][a-zA-Z0-9_]*)[\'"]\s*=>/', $content, $matches);
    $keys = $matches[1];
    
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

echo "📊 STATISTICHE AGGIORNATE:\n";
echo "==========================\n";
echo "File totali: " . count($files) . "\n";
echo "Chiavi totali: " . count($allKeys) . "\n";
echo "Chiavi duplicate: " . count($duplicates) . "\n\n";

if (count($duplicates) > 0) {
    echo "🚨 CHIAVI DUPLICATE RIMANENTI:\n";
    echo "===============================\n";
    
    $count = 0;
    foreach ($duplicates as $key => $files) {
        if ($count >= 20) {
            echo "... e " . (count($duplicates) - 20) . " altre\n";
            break;
        }
        echo "Key: '$key' in files: " . implode(', ', array_unique($files)) . "\n";
        $count++;
    }
    
    echo "\n💡 SUGGERIMENTI:\n";
    echo "================\n";
    echo "Le chiavi duplicate rimanenti potrebbero essere:\n";
    echo "1. Chiavi che hanno lo STESSO significato (da consolidare in common.php)\n";
    echo "2. Chiavi che hanno significati DIVERSI (da rinominare con prefissi)\n";
    echo "3. Chiavi che sono corrette così (es: 'role_*' per diversi ruoli)\n";
} else {
    echo "🎉 PERFETTO! Nessuna chiave duplicata rimanente!\n";
}

