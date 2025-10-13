<?php

echo "🔍 ANALISI SISTEMATICA TUTTE LE CHIAVI MANCANTI\n";
echo "===============================================\n\n";

// Directory da analizzare
$directories = ['resources/views', 'app/Http/Controllers', 'app/Livewire'];

$allUsedKeys = [];
$fileStats = [];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        echo "⚠️  Directory $dir non trovata\n";
        continue;
    }
    
    echo "📁 Analizzando: $dir\n";
    
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    $fileCount = 0;
    
    foreach ($files as $file) {
        if ($file->isFile() && in_array($file->getExtension(), ['php', 'blade.php'])) {
            $fileCount++;
            $content = file_get_contents($file->getPathname());
            $fileName = str_replace(getcwd() . '/', '', $file->getPathname());
            
            // Estrae tutte le chiavi di traduzione usate
            preg_match_all('/__\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);
            preg_match_all('/@lang\([\'"]([^\'"]+)[\'"]\)/', $content, $matches2);
            preg_match_all('/trans\([\'"]([^\'"]+)[\'"]\)/', $content, $matches3);
            
            $usedKeys = array_merge($matches[1], $matches2[1], $matches3[1]);
            $usedKeys = array_unique($usedKeys);
            
            foreach ($usedKeys as $key) {
                $allUsedKeys[] = $key;
            }
            
            if (count($usedKeys) > 0) {
                $fileStats[$fileName] = $usedKeys;
            }
        }
    }
    
    echo "   📊 File processati: $fileCount\n";
}

$allUsedKeys = array_unique($allUsedKeys);
sort($allUsedKeys);

echo "\n📊 STATISTICHE TOTALI:\n";
echo "======================\n";
echo "File analizzati: " . count($fileStats) . "\n";
echo "Chiavi uniche trovate: " . count($allUsedKeys) . "\n\n";

// Ora controlla quali chiavi mancano
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
        } elseif (count($keyParts) === 3) {
            // Chiave annidata: file.key.subkey
            $keyName = $keyParts[1];
            $subKey = $keyParts[2];
            
            // Cerca la sezione principale
            if (preg_match("/[\'\"]({$keyName})[\'"]\s*=>\s*\[/", $translationContent)) {
                // Controlla se la subkey esiste nella sezione
                $pattern = "/[\'\"]({$keyName})[\'"]\s*=>\s*\[(.*?)\]/s";
                if (preg_match($pattern, $translationContent, $matches)) {
                    $sectionContent = $matches[1];
                    if (preg_match("/[\'\"]({$subKey})[\'"]\s*=>/", $sectionContent)) {
                        $keyExists = true;
                    }
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

echo "📊 RISULTATI:\n";
echo "=============\n";
echo "Chiavi esistenti: " . count($existingKeys) . "\n";
echo "Chiavi mancanti: " . count($missingKeys) . "\n\n";

if (count($missingKeys) > 0) {
    echo "🚨 CHIAVI MANCANTI TROVATE:\n";
    echo "===========================\n";
    
    // Raggruppa per file di traduzione
    $byTranslationFile = [];
    foreach ($missingKeys as $key) {
        $keyParts = explode('.', $key);
        $file = $keyParts[0];
        $byTranslationFile[$file][] = $key;
    }
    
    foreach ($byTranslationFile as $translationFile => $keys) {
        echo "\n📄 $translationFile.php (" . count($keys) . " chiavi mancanti):\n";
        foreach ($keys as $key) {
            echo "   - $key\n";
        }
    }
    
    echo "\n🎯 AZIONI NECESSARIE:\n";
    echo "=====================\n";
    echo "1. Aggiungere tutte le chiavi mancanti ai file di traduzione\n";
    echo "2. Verificare che le traduzioni siano corrette\n";
    echo "3. Testare tutte le pagine\n";
} else {
    echo "✅ TUTTE LE CHIAVI ESISTONO!\n";
}

