<?php

echo "🔍 ANALISI SISTEMATICA CHIAVI MANCANTI\n";
echo "=====================================\n\n";

// Estrae tutte le chiavi di traduzione usate nel codice
$allKeys = [];

$directories = ['resources/views', 'app/Http/Controllers', 'app/Livewire'];

foreach ($directories as $dir) {
    if (!is_dir($dir)) continue;
    
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    
    foreach ($files as $file) {
        if ($file->isFile() && in_array($file->getExtension(), ['php', 'blade.php'])) {
            $content = file_get_contents($file->getPathname());
            
            // Estrae chiavi di traduzione
            preg_match_all('/__\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);
            preg_match_all('/@lang\([\'"]([^\'"]+)[\'"]\)/', $content, $matches2);
            preg_match_all('/trans\([\'"]([^\'"]+)[\'"]\)/', $content, $matches3);
            
            $keys = array_merge($matches[1], $matches2[1], $matches3[1]);
            $allKeys = array_merge($allKeys, $keys);
        }
    }
}

$allKeys = array_unique($allKeys);
sort($allKeys);

echo "📊 Chiavi di traduzione trovate: " . count($allKeys) . "\n\n";

// Controlla quali chiavi mancano
$missingKeys = [];

foreach ($allKeys as $key) {
    $keyParts = explode('.', $key);
    if (count($keyParts) >= 2) {
        $file = $keyParts[0];
        $translationFile = "lang/it/$file.php";
        
        if (!file_exists($translationFile)) {
            $missingKeys[] = $key;
            continue;
        }
        
        $translationContent = file_get_contents($translationFile);
        
        // Controlla se la chiave esiste
        $keyExists = false;
        
        if (count($keyParts) === 2) {
            // Chiave semplice
            $keyName = $keyParts[1];
            if (strpos($translationContent, "'$keyName'") !== false || strpos($translationContent, "\"$keyName\"") !== false) {
                $keyExists = true;
            }
        }
        
        if (!$keyExists) {
            $missingKeys[] = $key;
        }
    }
}

echo "📊 RISULTATI:\n";
echo "=============\n";
echo "Chiavi esistenti: " . (count($allKeys) - count($missingKeys)) . "\n";
echo "Chiavi mancanti: " . count($missingKeys) . "\n\n";

if (count($missingKeys) > 0) {
    echo "🚨 CHIAVI MANCANTI:\n";
    echo "===================\n";
    
    // Raggruppa per file
    $byFile = [];
    foreach ($missingKeys as $key) {
        $file = explode('.', $key)[0];
        $byFile[$file][] = $key;
    }
    
    foreach ($byFile as $file => $keys) {
        echo "\n📄 $file.php (" . count($keys) . " chiavi):\n";
        foreach (array_slice($keys, 0, 10) as $key) {
            echo "   - $key\n";
        }
        if (count($keys) > 10) {
            echo "   ... e " . (count($keys) - 10) . " altre\n";
        }
    }
} else {
    echo "✅ TUTTE LE CHIAVI ESISTONO!\n";
}

