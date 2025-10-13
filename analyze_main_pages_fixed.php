<?php

echo "🎯 ANALISI PAGINE PRINCIPALI\n";
echo "============================\n\n";

// File principali da analizzare
$mainFiles = [
    'resources/views/home.blade.php',
    'resources/views/layout/sidebar.blade.php', 
    'resources/views/layout/master.blade.php',
    'resources/views/events/index.blade.php',
    'resources/views/events/show.blade.php',
    'resources/views/auth/login.blade.php',
    'resources/views/auth/register.blade.php'
];

$issues = [];

foreach ($mainFiles as $file) {
    if (!file_exists($file)) {
        echo "⚠️  File $file non trovato\n";
        continue;
    }
    
    echo "📄 Analizzando: " . basename($file) . "\n";
    
    $content = file_get_contents($file);
    $fileName = basename($file);
    
    // 1. Trova testi con [en] prefix
    if (preg_match_all('/\[en\]\s*([^<\n]+)/', $content, $matches)) {
        echo "   🚨 Testi con [en] trovati:\n";
        foreach ($matches[1] as $match) {
            $text = trim($match);
            if (strlen($text) > 3) {
                echo "      - \"$text\"\n";
                $issues[] = [
                    'file' => $fileName,
                    'type' => '[en] prefix',
                    'text' => $text
                ];
            }
        }
    }
    
    // 2. Trova testi italiani hardcoded specifici
    $hardcodedPatterns = [
        'Prossimi Eventi',
        'Dettagli', 
        'Segnala',
        'Video recenti',
        'Cerca poesie, eventi',
        'Ingaggi',
        'Notizie',
        'Media',
        'Poems',
        'Utenze',
        'Gruppi',
        'Forum',
        'Didattica',
        'Supporto Fan',
        'Wiki',
        'Amministrazione',
        'Moderazione Forum'
    ];
    
    foreach ($hardcodedPatterns as $text) {
        // Cerca il testo hardcoded (non dentro __() o @lang())
        if (strpos($content, $text) !== false) {
            // Verifica che non sia già tradotto
            if (!preg_match('/__\([\'"].*' . preg_quote($text, '/') . '.*[\'"]\)/', $content) &&
                !preg_match('/@lang\([\'"].*' . preg_quote($text, '/') . '.*[\'"]\)/', $content)) {
                echo "   🔍 Testo hardcoded trovato: \"$text\"\n";
                $issues[] = [
                    'file' => $fileName,
                    'type' => 'hardcoded italian',
                    'text' => $text
                ];
            }
        }
    }
    
    echo "\n";
}

echo "📊 RIEPILOGO PROBLEMI PRINCIPALI:\n";
echo "==================================\n";
echo "Problemi totali trovati: " . count($issues) . "\n\n";

// Raggruppa per tipo
$byType = [];
foreach ($issues as $issue) {
    $byType[$issue['type']][] = $issue;
}

foreach ($byType as $type => $typeIssues) {
    echo "🔸 $type: " . count($typeIssues) . " problemi\n";
}

echo "\n🎯 PRIORITÀ DI INTERVENTO:\n";
echo "==========================\n";
echo "1. Sistemare testi con [en] prefix (più urgente)\n";
echo "2. Sostituire testi hardcoded italiani\n";
echo "3. Creare chiavi di traduzione mancanti\n";

