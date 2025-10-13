<?php

echo "🔍 RICERCA CHIAVI MANCANTI RIMANENTI\n";
echo "====================================\n\n";

// Controlla tutte le chiavi usate nel codice che potrebbero mancare
$allUsedKeys = [];

// Directory da analizzare
$directories = ['resources/views'];

foreach ($directories as $dir) {
    if (!is_dir($dir)) continue;
    
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    
    foreach ($files as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());
            
            // Estrae tutte le chiavi di traduzione usate
            preg_match_all('/__\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);
            preg_match_all('/@lang\([\'"]([^\'"]+)[\'"]\)/', $content, $matches2);
            preg_match_all('/trans\([\'"]([^\'"]+)[\'"]\)/', $content, $matches3);
            
            $usedKeys = array_merge($matches[1], $matches2[1], $matches3[1]);
            
            foreach ($usedKeys as $key) {
                $allUsedKeys[] = $key;
            }
        }
    }
}

$allUsedKeys = array_unique($allUsedKeys);
sort($allUsedKeys);

echo "📊 Chiavi di traduzione usate nel codice: " . count($allUsedKeys) . "\n\n";

// Controlla quali chiavi mancano
$missingKeys = [];
$existingKeys = [];

foreach ($allUsedKeys as $key) {
    $keyParts = explode('.', $key);
    if (count($keyParts) >= 2) {
        $file = $keyParts[0];
        $translationFile = "lang/it/$file.php";
        
        if (!file_exists($translationFile)) {
            $missingKeys[] = $key;
            continue;
        }
        
        $translationContent = file_get_contents($translationFile);
        
        // Controlla se la chiave esiste nel file
        $keyExists = false;
        if (count($keyParts) === 2) {
            // Chiave semplice: file.key
            $keyName = $keyParts[1];
            if (preg_match("/[\'\"]({$keyName})[\'"]\s*=>/", $translationContent)) {
                $keyExists = true;
            }
        } else {
            // Chiave annidata: file.key.subkey
            $keyName = $keyParts[1];
            $subKey = $keyParts[2];
            if (preg_match("/[\'\"]({$keyName})[\'"]\s*=>\s*\[/", $translationContent)) {
                // Controlla se la subkey esiste
                if (preg_match("/[\'\"]({$subKey})[\'"]\s*=>/", $translationContent)) {
                    $keyExists = true;
                }
            }
        }
        
        if (!$keyExists) {
            $missingKeys[] = $key;
        } else {
            $existingKeys[] = $key;
        }
    }
}

echo "📊 STATISTICHE:\n";
echo "===============\n";
echo "Chiavi esistenti: " . count($existingKeys) . "\n";
echo "Chiavi mancanti: " . count($missingKeys) . "\n\n";

if (count($missingKeys) > 0) {
    echo "🚨 CHIAVI MANCANTI (TOP 20):\n";
    echo "=============================\n";
    
    $topMissing = array_slice($missingKeys, 0, 20);
    foreach ($topMissing as $key) {
        echo "   - $key\n";
    }
    
    if (count($missingKeys) > 20) {
        echo "   ... e " . (count($missingKeys) - 20) . " altre\n";
    }
    
    echo "\n🎯 AZIONI NECESSARIE:\n";
    echo "=====================\n";
    echo "1. Aggiungere le chiavi mancanti ai file di traduzione\n";
    echo "2. Verificare che le traduzioni siano corrette\n";
    echo "3. Testare che le pagine funzionino\n";
} else {
    echo "✅ TUTTE LE CHIAVI ESISTONO!\n";
}

