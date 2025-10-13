<?php

echo "🔄 AGGIORNAMENTO RIFERIMENTI TRADUZIONI\n";
echo "=======================================\n\n";

// Mappatura dei file rinominati
$fileMappings = [
    'events' => 'events_general',
    'admin' => 'admin_general', 
    'chat' => 'chat_general'
];

// Directory da processare
$directories = ['resources/views', 'app/Http/Controllers', 'app/Livewire'];

$totalFiles = 0;
$totalUpdates = 0;

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        echo "⚠️  Directory $dir non trovata\n";
        continue;
    }
    
    echo "📁 Processando: $dir\n";
    
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    $fileCount = 0;
    $updateCount = 0;
    
    foreach ($files as $file) {
        if ($file->isFile() && in_array($file->getExtension(), ['php', 'blade.php'])) {
            $fileCount++;
            $content = file_get_contents($file->getPathname());
            $originalContent = $content;
            
            // Aggiorna i riferimenti
            foreach ($fileMappings as $oldFile => $newFile) {
                // Pattern per __('oldfile.key')
                $pattern1 = '/__\([\'"]([^\'"]*)\b' . preg_quote($oldFile, '/') . '\b([^\'"]*)[\'"]\)/';
                $replacement1 = '__(\'$1' . $newFile . '$2\')';
                $content = preg_replace($pattern1, $replacement1, $content);
                
                // Pattern per @lang('oldfile.key')
                $pattern2 = '/@lang\([\'"]([^\'"]*)\b' . preg_quote($oldFile, '/') . '\b([^\'"]*)[\'"]\)/';
                $replacement2 = '@lang(\'$1' . $newFile . '$2\')';
                $content = preg_replace($pattern2, $replacement2, $content);
                
                // Pattern per trans('oldfile.key')
                $pattern3 = '/trans\([\'"]([^\'"]*)\b' . preg_quote($oldFile, '/') . '\b([^\'"]*)[\'"]\)/';
                $replacement3 = 'trans(\'$1' . $newFile . '$2\')';
                $content = preg_replace($pattern3, $replacement3, $content);
            }
            
            // Salva se ci sono state modifiche
            if ($content !== $originalContent) {
                file_put_contents($file->getPathname(), $content);
                $updateCount++;
                
                // Conta le modifiche
                $changes = 0;
                foreach ($fileMappings as $oldFile => $newFile) {
                    $changes += substr_count($originalContent, "'$oldFile.") + substr_count($originalContent, "\"$oldFile.");
                    $changes -= substr_count($content, "'$oldFile.") + substr_count($content, "\"$oldFile.");
                }
                
                if ($changes > 0) {
                    echo "   ✅ " . basename($file->getPathname()) . " ($changes modifiche)\n";
                }
            }
        }
    }
    
    echo "   📊 File processati: $fileCount, File aggiornati: $updateCount\n\n";
    $totalFiles += $fileCount;
    $totalUpdates += $updateCount;
}

echo "📊 RIEPILOGO FINALE:\n";
echo "====================\n";
echo "File totali processati: $totalFiles\n";
echo "File aggiornati: $totalUpdates\n\n";

echo "🎯 AGGIORNAMENTI APPLICATI:\n";
echo "===========================\n";
foreach ($fileMappings as $oldFile => $newFile) {
    echo "✅ $oldFile.* → $newFile.*\n";
}

echo "\n🚀 PROSSIMO STEP:\n";
echo "==================\n";
echo "Testare che tutte le pagine funzionino correttamente\n";
echo "e che le traduzioni vengano caricate senza errori\n";

