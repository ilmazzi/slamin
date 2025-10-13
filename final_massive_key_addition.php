<?php
echo "🚀 AGGIUNTA MASSIVA FINALE TUTTE LE CHIAVI RIMANENTI\n";
echo "====================================================\n\n";

// Ottieni tutte le chiavi mancanti
$output = shell_exec('php find_missing_keys_simple.php 2>&1');
$lines = explode("\n", $output);

$allMissingKeys = [];
$currentFile = '';

foreach ($lines as $line) {
    if (strpos($line, '📄') !== false) {
        preg_match('/📄 ([^.]+)\.php/', $line, $matches);
        if (isset($matches[1])) {
            $currentFile = $matches[1];
        }
    } elseif (strpos($line, '   - ') === 0) {
        $key = trim(str_replace('   - ', '', $line));
        if ($key && $currentFile) {
            $allMissingKeys[$currentFile][] = $key;
        }
    }
}

$totalAdded = 0;

foreach ($allMissingKeys as $file => $keys) {
    if (empty($keys)) continue;
    
    $filePath = "lang/it/$file.php";
    
    if (!file_exists($filePath)) continue;
    
    echo "📄 $file.php: aggiungendo " . count($keys) . " chiavi...\n";
    
    $content = file_get_contents($filePath);
    $content = rtrim($content, '];');
    
    $added = 0;
    
    foreach ($keys as $key) {
        $keyParts = explode('.', $key);
        $keyName = $keyParts[count($keyParts) - 1];
        
        if (strpos($content, "'$keyName'") === false && strpos($content, "\"$keyName\"") === false) {
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
    }
}

echo "\n🎉 RISULTATO FINALE:\n";
echo "====================\n";
echo "Chiavi aggiunte in totale: $totalAdded\n";
echo "File aggiornati: " . count($allMissingKeys) . "\n\n";

echo "🔍 Verifica finale...\n";
