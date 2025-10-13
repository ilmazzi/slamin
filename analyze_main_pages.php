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
    
    // 2. Trova testi italiani hardcoded (più specifici)
    $italianTexts = [
        'Prossimi Eventi', 'Eventi', 'Dettagli', 'Segnala', 'Video recenti',
        'Notizie', 'Media', 'Poems', 'Utenze', 'Gruppi', 'Forum', 'Didattica',
        'Supporto Fan', 'Wiki', 'Amministrazione', 'Moderazione Forum',
        'Cerca', 'Impostazioni', 'Logout', 'Login', 'Registrati', 'Salva',
        'Annulla', 'Modifica', 'Elimina', 'Visualizza', 'Crea', 'Gestisci'
    ];
    
    foreach ($italianTexts as $text) {
        // Cerca il testo hardcoded (non dentro __() o @lang())
        $pattern = '/(?<!__\([\'"])(?<!@lang\([\'"])(?<!trans\([\'"])\b' . preg_quote($text, '/') . '\b(?![\'"]\))/';
        if (preg_match_all($pattern, $content, $matches)) {
            echo "   🔍 Testo hardcoded trovato: \"$text\"\n";
            $issues[] = [
                'file' => $fileName,
                'type' => 'hardcoded italian',
                'text' => $text
            ];
        }
    }
    
    // 3. Trova riferimenti a traduzioni che potrebbero non esistere
    if (preg_match_all('/__\([\'"]([^\'"]+)[\'"]\)/', $content, $matches)) {
        $uniqueKeys = array_unique($matches[1]);
        foreach ($uniqueKeys as $key) {
            $keyParts = explode('.', $key);
            if (count($keyParts) >= 2) {
                $file = $keyParts[0];
                $translationFile = "lang/it/$file.php";
                
                if (!file_exists($translationFile)) {
                    echo "   ❌ File traduzione mancante: $translationFile (chiave: $key)\n";
                    $issues[] = [
                        'file' => $fileName,
                        'type' => 'missing translation file',
                        'text' => "$key -> $translationFile"
                    ];
                } else {
                    // Verifica che la chiave esista nel file
                    $translationContent = file_get_contents($translationFile);
                    $keyName = $keyParts[1];
                    if (!preg_match("/[\'\"]({$keyName})[\'"]\s*=>/", $translationContent)) {
                        echo "   ❌ Chiave non trovata: $key\n";
                        $issues[] = [
                            'file' => $fileName,
                            'type' => 'missing key',
                            'text' => $key
                        ];
                    }
                }
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
echo "4. Verificare tutti i file di traduzione\n";

