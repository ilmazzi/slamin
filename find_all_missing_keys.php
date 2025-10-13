<?php

echo "🔍 RICERCA SISTEMATICA CHIAVI MANCANTI\n";
echo "======================================\n\n";

// Directory da analizzare
$directories = ['resources/views'];

$missingKeys = [];
$allTranslationKeys = [];

// Prima, raccogli tutte le chiavi di traduzione esistenti
$langFiles = glob('lang/it/*.php');
foreach ($langFiles as $file) {
    $fileName = basename($file, '.php');
    $content = file_get_contents($file);
    
    preg_match_all('/[\'"]([a-zA-Z_][a-zA-Z0-9_]*)[\'"]\s*=>/', $content, $matches);
    foreach ($matches[1] as $key) {
        $allTranslationKeys[$fileName][] = $key;
    }
}

echo "📊 Chiavi di traduzione esistenti per file:\n";
foreach ($allTranslationKeys as $file => $keys) {
    echo "   $file.php: " . count($keys) . " chiavi\n";
}

echo "\n🔍 Analizzando riferimenti nel codice...\n";

foreach ($directories as $dir) {
    if (!is_dir($dir)) continue;
    
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    
    foreach ($files as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());
            $fileName = str_replace(getcwd() . '/', '', $file->getPathname());
            
            // Estrae tutte le chiavi di traduzione usate
            preg_match_all('/__\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);
            preg_match_all('/@lang\([\'"]([^\'"]+)[\'"]\)/', $content, $matches2);
            preg_match_all('/trans\([\'"]([^\'"]+)[\'"]\)/', $content, $matches3);
            
            $usedKeys = array_merge($matches[1], $matches2[1], $matches3[1]);
            $usedKeys = array_unique($usedKeys);
            
            foreach ($usedKeys as $key) {
                $keyParts = explode('.', $key);
                if (count($keyParts) >= 2) {
                    $file = $keyParts[0];
                    $keyName = $keyParts[1];
                    
                    // Verifica se la chiave esiste
                    if (!isset($allTranslationKeys[$file]) || 
                        !in_array($keyName, $allTranslationKeys[$file])) {
                        
                        $missingKeys[] = [
                            'file' => $fileName,
                            'key' => $key,
                            'translation_file' => $file,
                            'key_name' => $keyName
                        ];
                    }
                }
            }
        }
    }
}

echo "\n📊 RISULTATI:\n";
echo "==============\n";
echo "Chiavi mancanti trovate: " . count($missingKeys) . "\n\n";

if (count($missingKeys) > 0) {
    echo "🚨 CHIAVI MANCANTI:\n";
    echo "===================\n";
    
    // Raggruppa per file di traduzione
    $byTranslationFile = [];
    foreach ($missingKeys as $missing) {
        $byTranslationFile[$missing['translation_file']][] = $missing;
    }
    
    foreach ($byTranslationFile as $translationFile => $keys) {
        echo "\n📄 $translationFile.php:\n";
        foreach ($keys as $key) {
            echo "   - {$key['key_name']} (usato in {$key['file']})\n";
        }
    }
    
    echo "\n🎯 AZIONI NECESSARIE:\n";
    echo "=====================\n";
    echo "1. Aggiungere le chiavi mancanti ai file di traduzione\n";
    echo "2. Verificare che le traduzioni siano corrette\n";
    echo "3. Testare che le pagine funzionino\n";
} else {
    echo "✅ TUTTE LE CHIAVI ESISTONO!\n";
}

