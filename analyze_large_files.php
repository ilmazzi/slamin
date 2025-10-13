<?php

echo "📊 ANALISI FILE SOVRADIMENSIONATI\n";
echo "=================================\n\n";

$langPath = 'lang/it';

// Analizza i file più grandi
$files = glob($langPath . '/*.php');
$fileStats = [];

foreach ($files as $file) {
    if (strpos($file, '.backup.') !== false) continue;
    
    $fileName = basename($file, '.php');
    $content = file_get_contents($file);
    
    // Conta le chiavi
    preg_match_all('/[\'"]([a-zA-Z_][a-zA-Z0-9_]*)[\'"]\s*=>/', $content, $matches);
    $keyCount = count($matches[1]);
    
    $fileStats[$fileName] = [
        'keys' => $keyCount,
        'size' => strlen($content),
        'lines' => substr_count($content, "\n")
    ];
}

// Ordina per numero di chiavi
uasort($fileStats, function($a, $b) {
    return $b['keys'] - $a['keys'];
});

echo "🔥 TOP 10 FILE PIÙ GRANDI:\n";
echo "=========================\n";

$count = 0;
foreach ($fileStats as $fileName => $stats) {
    if ($count >= 10) break;
    echo sprintf("%2d. %-20s: %3d chiavi, %5d bytes, %3d righe\n", 
        ++$count, $fileName, $stats['keys'], $stats['size'], $stats['lines']);
}

echo "\n🎯 FILE DA RIORGANIZZARE:\n";
echo "=========================\n";

// File che superano i 200 chiavi dovrebbero essere divisi
foreach ($fileStats as $fileName => $stats) {
    if ($stats['keys'] > 200) {
        echo "⚠️  $fileName.php: {$stats['keys']} chiavi (troppo grande)\n";
        
        // Suggerisci come dividerlo
        switch ($fileName) {
            case 'events':
                echo "   → Suggerimento: events.php, events_management.php, events_scoring.php\n";
                break;
            case 'admin':
                echo "   → Suggerimento: admin.php, admin_users.php, admin_content.php\n";
                break;
            case 'chat':
                echo "   → Suggerimento: chat.php, chat_moderation.php\n";
                break;
            default:
                echo "   → Suggerimento: Dividere per funzionalità\n";
        }
        echo "\n";
    }
}

