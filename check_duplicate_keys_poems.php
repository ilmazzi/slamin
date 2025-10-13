<?php
$content = file_get_contents('lang/it/poems.php');

// Estrae tutte le chiavi
preg_match_all("/^\s*'([^']+)'\s*=>/m", $content, $matches);

$keys = $matches[1];
$duplicates = [];

foreach ($keys as $key) {
    $count = substr_count($content, "'$key' =>");
    if ($count > 1) {
        $duplicates[$key] = $count;
    }
}

if (empty($duplicates)) {
    echo "✅ Nessuna chiave duplicata trovata!\n";
} else {
    echo "🚨 CHIAVI DUPLICATE TROVATE:\n";
    foreach ($duplicates as $key => $count) {
        echo "   - '$key' appare $count volte\n";
    }
}
