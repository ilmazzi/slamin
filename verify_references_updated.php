<?php

echo "✅ VERIFICA AGGIORNAMENTO RIFERIMENTI\n";
echo "=====================================\n\n";

// Directory da verificare
$directories = ['resources/views', 'app/Http/Controllers', 'app/Livewire'];

$oldReferences = [];
$newReferences = [];

foreach ($directories as $dir) {
    if (!is_dir($dir)) continue;
    
    echo "📁 Verificando: $dir\n";
    
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    
    foreach ($files as $file) {
        if ($file->isFile() && in_array($file->getExtension(), ['php', 'blade.php'])) {
            $content = file_get_contents($file->getPathname());
            
            // Cerca riferimenti ai file vecchi (non dovrebbero esserci)
            if (preg_match('/__\([\'"][^\'"]*\b(events|admin|chat)\b[^\'"]*[\'"]\)/', $content, $matches)) {
                $oldReferences[] = [
                    'file' => str_replace(getcwd() . '/', '', $file->getPathname()),
                    'match' => $matches[0]
                ];
            }
            
            // Cerca riferimenti ai file nuovi
            if (preg_match('/__\([\'"][^\'"]*\b(events_general|admin_general|chat_general)\b[^\'"]*[\'"]\)/', $content, $matches)) {
                $newReferences[] = [
                    'file' => str_replace(getcwd() . '/', '', $file->getPathname()),
                    'match' => $matches[0]
                ];
            }
        }
    }
}

echo "\n📊 RISULTATI:\n";
echo "==============\n";
echo "Riferimenti vecchi trovati: " . count($oldReferences) . "\n";
echo "Riferimenti nuovi trovati: " . count($newReferences) . "\n\n";

if (count($oldReferences) > 0) {
    echo "🚨 RIFERIMENTI VECCHI RIMANENTI:\n";
    echo "=================================\n";
    foreach ($oldReferences as $ref) {
        echo "File: " . $ref['file'] . "\n";
        echo "Match: " . $ref['match'] . "\n\n";
    }
} else {
    echo "🎉 PERFETTO! Nessun riferimento vecchio trovato!\n";
}

if (count($newReferences) > 0) {
    echo "✅ RIFERIMENTI NUOVI CONFERMATI:\n";
    echo "=================================\n";
    $examples = array_slice($newReferences, 0, 5);
    foreach ($examples as $ref) {
        echo "File: " . $ref['file'] . "\n";
        echo "Match: " . $ref['match'] . "\n\n";
    }
    
    if (count($newReferences) > 5) {
        echo "... e " . (count($newReferences) - 5) . " altri\n";
    }
}

echo "\n🎯 STATO FINALE:\n";
echo "=================\n";
if (count($oldReferences) === 0) {
    echo "✅ Tutti i riferimenti aggiornati con successo!\n";
    echo "✅ Sistema di traduzioni completamente migrato!\n";
    echo "✅ Pronto per il test finale!\n";
} else {
    echo "⚠️  Alcuni riferimenti vecchi sono rimasti\n";
    echo "⚠️  Potrebbe essere necessario aggiornamento manuale\n";
}

