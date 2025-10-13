<?php

echo "🔍 ANALISI COMPLETA PROBLEMI TRADUZIONI\n";
echo "=======================================\n\n";

// Directory da analizzare
$directories = ['resources/views'];

$issues = [
    'hardcoded_texts' => [],
    'missing_translations' => [],
    'unused_keys' => []
];

$totalFiles = 0;
$totalIssues = 0;

foreach ($directories as $dir) {
    if (!is_dir($dir)) continue;
    
    echo "📁 Analizzando: $dir\n";
    
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    
    foreach ($files as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $totalFiles++;
            $content = file_get_contents($file->getPathname());
            $fileName = str_replace(getcwd() . '/', '', $file->getPathname());
            
            // 1. Trova testi hardcoded (stringhe italiane non tradotte)
            $hardcodedPatterns = [
                // Pattern per testi italiani comuni
                '/([A-Z][a-zàèéìíîòóùú][a-zàèéìíîòóùú\s]{10,})/u',
                // Pattern per bottoni e link
                '/(>[A-Z][a-zàèéìíîòóùú][a-zàèéìíîòóùú\s]{5,}<)/u',
                // Pattern per placeholder
                '/(placeholder=["\'][A-Z][a-zàèéìíîòóùú][^"\']{5,}["\'])/u'
            ];
            
            foreach ($hardcodedPatterns as $pattern) {
                if (preg_match_all($pattern, $content, $matches)) {
                    foreach ($matches[1] as $match) {
                        // Escludi testo già tradotto o codice
                        if (!preg_match('/^__\(|@lang\(|trans\(|{{|{!!/', $match) && 
                            !preg_match('/[{}]/', $match) &&
                            strlen(trim($match)) > 8) {
                            
                            $issues['hardcoded_texts'][] = [
                                'file' => $fileName,
                                'text' => trim($match),
                                'line' => 'N/A' // Sarebbe meglio calcolare la riga
                            ];
                            $totalIssues++;
                        }
                    }
                }
            }
            
            // 2. Trova chiavi con [en] prefix (chiavi mancanti)
            if (preg_match_all('/\[en\][\s]*([^<\n]+)/', $content, $matches)) {
                foreach ($matches[1] as $match) {
                    $issues['missing_translations'][] = [
                        'file' => $fileName,
                        'text' => trim($match),
                        'type' => 'missing_translation'
                    ];
                    $totalIssues++;
                }
            }
            
            // 3. Trova riferimenti a traduzioni che potrebbero non esistere
            if (preg_match_all('/__\([\'"]([^\'"]+)[\'"]\)/', $content, $matches)) {
                foreach ($matches[1] as $key) {
                    // Controlla se la chiave esiste nei file di traduzione
                    $keyParts = explode('.', $key);
                    if (count($keyParts) >= 2) {
                        $file = $keyParts[0];
                        $translationFile = "lang/it/$file.php";
                        
                        if (!file_exists($translationFile)) {
                            $issues['missing_translations'][] = [
                                'file' => $fileName,
                                'text' => "File $translationFile non trovato per chiave '$key'",
                                'type' => 'missing_file'
                            ];
                            $totalIssues++;
                        }
                    }
                }
            }
        }
    }
}

echo "\n📊 RISULTATI ANALISI:\n";
echo "======================\n";
echo "File analizzati: $totalFiles\n";
echo "Problemi totali trovati: $totalIssues\n";
echo "Testi hardcoded: " . count($issues['hardcoded_texts']) . "\n";
echo "Traduzioni mancanti: " . count($issues['missing_translations']) . "\n\n";

echo "🚨 TOP 20 TESTI HARDCODED TROVATI:\n";
echo "===================================\n";

// Mostra i primi 20 testi hardcoded
$hardcoded = array_slice($issues['hardcoded_texts'], 0, 20);
foreach ($hardcoded as $issue) {
    echo "File: " . $issue['file'] . "\n";
    echo "Testo: \"" . $issue['text'] . "\"\n\n";
}

echo "🔍 TRADUZIONI MANCANTI:\n";
echo "========================\n";

// Mostra le traduzioni mancanti
$missing = array_slice($issues['missing_translations'], 0, 10);
foreach ($missing as $issue) {
    echo "File: " . $issue['file'] . "\n";
    echo "Problema: " . $issue['text'] . "\n\n";
}

echo "🎯 PROSSIMI PASSI:\n";
echo "==================\n";
echo "1. Analizzare i testi hardcoded trovati\n";
echo "2. Creare chiavi di traduzione per i testi italiani\n";
echo "3. Sostituire testi hardcoded con chiavi di traduzione\n";
echo "4. Verificare che tutte le chiavi esistano nei file\n";

