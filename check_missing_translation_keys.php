<?php

echo "🔍 VERIFICA CHIAVI TRADUZIONE MANCANTI\n";
echo "======================================\n\n";

// File principali da verificare
$filesToCheck = [
    'resources/views/home.blade.php',
    'resources/views/layout/sidebar.blade.php',
    'resources/views/events/index.blade.php',
    'resources/views/events/show.blade.php'
];

$missingKeys = [];
$existingKeys = [];

foreach ($filesToCheck as $file) {
    if (!file_exists($file)) {
        echo "⚠️  File $file non trovato\n";
        continue;
    }
    
    echo "📄 Verificando: " . basename($file) . "\n";
    
    $content = file_get_contents($file);
    
    // Estrae tutte le chiavi di traduzione
    preg_match_all('/__\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);
    preg_match_all('/@lang\([\'"]([^\'"]+)[\'"]\)/', $content, $matches2);
    preg_match_all('/trans\([\'"]([^\'"]+)[\'"]\)/', $content, $matches3);
    
    $allKeys = array_merge($matches[1], $matches2[1], $matches3[1]);
    
    foreach ($allKeys as $key) {
        $keyParts = explode('.', $key);
        if (count($keyParts) >= 2) {
            $file = $keyParts[0];
            $keyName = $keyParts[1];
            $translationFile = "lang/it/$file.php";
            
            if (!file_exists($translationFile)) {
                echo "   ❌ File mancante: $translationFile (chiave: $key)\n";
                $missingKeys[] = $key;
            } else {
                $translationContent = file_get_contents($translationFile);
                if (!preg_match("/[\'\"]({$keyName})[\'"]\s*=>/", $translationContent)) {
                    echo "   ❌ Chiave mancante: $key\n";
                    $missingKeys[] = $key;
                } else {
                    $existingKeys[] = $key;
                }
            }
        }
    }
    
    echo "\n";
}

echo "📊 RIEPILOGO:\n";
echo "=============\n";
echo "Chiavi esistenti: " . count($existingKeys) . "\n";
echo "Chiavi mancanti: " . count($missingKeys) . "\n\n";

if (count($missingKeys) > 0) {
    echo "🚨 CHIAVI MANCANTI DA CREARE:\n";
    echo "=============================\n";
    foreach ($missingKeys as $key) {
        echo "   - $key\n";
    }
    
    echo "\n🎯 AZIONI NECESSARIE:\n";
    echo "=====================\n";
    echo "1. Creare le chiavi mancanti nei file di traduzione\n";
    echo "2. Verificare che i file di traduzione esistano\n";
    echo "3. Testare che le traduzioni vengano visualizzate\n";
} else {
    echo "✅ TUTTE LE CHIAVI ESISTONO!\n";
    echo "Il problema potrebbe essere:\n";
    echo "1. Cache delle traduzioni\n";
    echo "2. Configurazione locale\n";
    echo "3. File di traduzione corrotti\n";
}

