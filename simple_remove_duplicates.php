<?php

echo "🔄 RIMOZIONE DUPLICATI SEMPLIFICATA\n";
echo "===================================\n\n";

$langPath = 'lang/it';

// Chiavi da rimuovere (ora in common.php)
$commonKeys = ['delete', 'view', 'edit', 'cancel', 'save', 'close', 'ok', 'actions', 'status', 'preview', 'search_placeholder'];

// File da processare
$files = glob($langPath . '/*.php');
$files = array_filter($files, function($file) {
    return basename($file, '.php') !== 'common' && strpos($file, '.backup.') === false;
});

echo "📁 File da processare: " . count($files) . "\n\n";

$totalRemoved = 0;

foreach ($files as $file) {
    $fileName = basename($file, '.php');
    echo "🔧 Processando: $fileName.php\n";
    
    $content = file_get_contents($file);
    $originalLines = explode("\n", $content);
    $newLines = [];
    $removedCount = 0;
    
    foreach ($originalLines as $line) {
        $shouldKeep = true;
        
        foreach ($commonKeys as $key) {
            // Controlla se la riga contiene una chiave da rimuovere
            if (preg_match('/^\s*[\'"](.*)[\'"]\s*=>/', $line, $matches)) {
                if ($matches[1] === $key) {
                    $shouldKeep = false;
                    $removedCount++;
                    break;
                }
            }
        }
        
        if ($shouldKeep) {
            $newLines[] = $line;
        }
    }
    
    if ($removedCount > 0) {
        $newContent = implode("\n", $newLines);
        file_put_contents($file, $newContent);
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
echo "File processati: " . count($files) . "\n";

