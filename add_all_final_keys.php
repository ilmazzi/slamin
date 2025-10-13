<?php
echo "🔧 AGGIUNTA TUTTE LE CHIAVI RIMANENTI\n";
echo "=====================================\n\n";

// Estrae tutte le chiavi mancanti dal nostro script
$output = shell_exec('php find_missing_keys_simple.php 2>&1');
$lines = explode("\n", $output);

$missingByFile = [];
$currentFile = '';

foreach ($lines as $line) {
    if (strpos($line, '📄') !== false) {
        // Estrae il nome del file
        preg_match('/📄 ([^.]+)\.php/', $line, $matches);
        if (isset($matches[1])) {
            $currentFile = $matches[1];
            $missingByFile[$currentFile] = [];
        }
    } elseif (strpos($line, '   - ') === 0) {
        // Estrae la chiave
        $key = trim(str_replace('   - ', '', $line));
        if ($key && $currentFile) {
            $missingByFile[$currentFile][] = $key;
        }
    }
}

$totalAdded = 0;

foreach ($missingByFile as $file => $keys) {
    if (empty($keys)) continue;
    
    $filePath = "lang/it/$file.php";
    
    if (!file_exists($filePath)) {
        echo "❌ File $filePath non trovato\n";
        continue;
    }
    
    echo "📄 Aggiornando: $file.php (" . count($keys) . " chiavi)\n";
    
    $content = file_get_contents($filePath);
    $content = rtrim($content, '];');
    
    $added = 0;
    
    foreach ($keys as $key) {
        $keyParts = explode('.', $key);
        $keyName = $keyParts[count($keyParts) - 1];
        
        if (strpos($content, "'$keyName'") === false && strpos($content, "\"$keyName\"") === false) {
            // Genera una traduzione automatica
            $translation = ucfirst(str_replace('_', ' ', $keyName));
            $content .= "\n    '$keyName' => '$translation',";
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

echo "\n📊 RIEPILOGO FINALE:\n";
echo "====================\n";
echo "Chiavi aggiunte: $totalAdded\n";
echo "File aggiornati: " . count($missingByFile) . "\n\n";

echo "🎯 VERIFICA FINALE:\n";
echo "===================\n";
echo "Controllando chiavi mancanti rimanenti...\n";
