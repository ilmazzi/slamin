<?php

echo "🔍 ANALISI RIFERIMENTI NEL CODICE\n";
echo "=================================\n\n";

// Directory da analizzare
$directories = ['resources/views', 'app/Http/Controllers', 'app/Livewire', 'app/Models'];

$references = [];
$totalFiles = 0;
$totalReferences = 0;

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        echo "⚠️  Directory $dir non trovata\n";
        continue;
    }
    
    echo "📁 Analizzando: $dir\n";
    
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    $fileCount = 0;
    $refCount = 0;
    
    foreach ($files as $file) {
        if ($file->isFile() && in_array($file->getExtension(), ['php', 'blade.php'])) {
            $fileCount++;
            $content = file_get_contents($file->getPathname());
            
            // Trova riferimenti a traduzioni
            preg_match_all('/__\([\'"]([^\'"]+)\.[^\'"]+[\'"]\)/', $content, $matches);
            foreach ($matches[1] as $fileRef) {
                $references[$fileRef][] = $file->getPathname();
                $refCount++;
            }
            
            // Trova anche @lang() e trans()
            preg_match_all('/(@lang|trans)\([\'"]([^\'"]+)\.[^\'"]+[\'"]\)/', $content, $matches2);
            foreach ($matches2[2] as $fileRef) {
                $references[$fileRef][] = $file->getPathname();
                $refCount++;
            }
        }
    }
    
    echo "   📊 File: $fileCount, Riferimenti: $refCount\n";
    $totalFiles += $fileCount;
    $totalReferences += $refCount;
}

echo "\n📊 STATISTICHE TOTALI:\n";
echo "======================\n";
echo "File analizzati: $totalFiles\n";
echo "Riferimenti trovati: $totalReferences\n";
echo "File di traduzione referenziati: " . count($references) . "\n\n";

echo "🔍 RIFERIMENTI PER FILE:\n";
echo "========================\n";

// Mostra i file che sono stati rinominati/divisi
$renamedFiles = [
    'events' => ['events_general', 'events_management', 'events_scoring', 'events_gamification'],
    'admin' => ['admin_general', 'admin_users', 'admin_content', 'admin_system'],
    'chat' => ['chat_general', 'chat_moderation']
];

foreach ($renamedFiles as $oldFile => $newFiles) {
    if (isset($references[$oldFile])) {
        echo "🚨 $oldFile.php (DIVISO):\n";
        echo "   Riferimenti trovati: " . count($references[$oldFile]) . "\n";
        echo "   Nuovi file: " . implode(', ', $newFiles) . "\n";
        echo "   Esempi di file che lo usano:\n";
        
        $examples = array_slice($references[$oldFile], 0, 3);
        foreach ($examples as $example) {
            echo "     - " . str_replace(getcwd() . '/', '', $example) . "\n";
        }
        echo "\n";
    }
}

// Mostra altri file con molti riferimenti
arsort($references);
echo "📋 ALTRI FILE CON MOLTI RIFERIMENTI:\n";
echo "====================================\n";

$count = 0;
foreach ($references as $file => $refs) {
    if ($count >= 10) break;
    if (!isset($renamedFiles[$file]) && count($refs) > 5) {
        echo "$file.php: " . count($refs) . " riferimenti\n";
        $count++;
    }
}

echo "\n🎯 PIANO DI AGGIORNAMENTO:\n";
echo "===========================\n";
echo "1. Aggiornare riferimenti da 'events.*' a 'events_general.*'\n";
echo "2. Aggiornare riferimenti da 'admin.*' a 'admin_general.*'\n";
echo "3. Aggiornare riferimenti da 'chat.*' a 'chat_general.*'\n";
echo "4. Verificare che tutti i riferimenti funzionino\n";

