<?php

echo "📂 DIVISIONE FILE CHAT.PHP\n";
echo "===========================\n\n";

$langPath = 'lang/it';
$chatFile = $langPath . '/chat.php';

if (!file_exists($chatFile)) {
    echo "❌ File chat.php non trovato!\n";
    exit;
}

$content = file_get_contents($chatFile);
$lines = explode("\n", $content);

// Categorie per dividere le chiavi
$categories = [
    'general' => [],      // Chat generale
    'moderation' => []    // Moderazione chat
];

$phpHeader = "<?php\n\nreturn [\n";
$phpFooter = "\n];\n";

// Pattern per identificare le categorie
$categoryPatterns = [
    'moderation' => ['moderate', 'ban', 'report', 'spam', 'admin', 'warning']
];

foreach ($lines as $line) {
    // Salta righe vuote e commenti
    if (trim($line) === '' || strpos($line, '//') === 0 || strpos($line, '<?php') === 0 || strpos($line, 'return [') === 0 || strpos($line, '];') === 0) {
        continue;
    }
    
    // Estrai la chiave dalla riga
    if (preg_match('/[\'"]([a-zA-Z_][a-zA-Z0-9_]*)[\'"]\s*=>/', $line, $matches)) {
        $key = $matches[1];
        
        // Determina la categoria
        $assignedCategory = 'general';
        foreach ($categoryPatterns as $category => $patterns) {
            foreach ($patterns as $pattern) {
                if (strpos($key, $pattern) !== false) {
                    $assignedCategory = $category;
                    break 2;
                }
            }
        }
        
        $categories[$assignedCategory][] = $line;
    }
}

// Crea i file separati
$filesCreated = 0;

// Chat generale
if (!empty($categories['general'])) {
    $generalContent = $phpHeader . implode("\n", $categories['general']) . $phpFooter;
    file_put_contents($langPath . '/chat_general.php', $generalContent);
    echo "✅ Creato chat_general.php con " . count($categories['general']) . " chiavi\n";
    $filesCreated++;
}

// Chat moderation
if (!empty($categories['moderation'])) {
    $moderationContent = $phpHeader . implode("\n", $categories['moderation']) . $phpFooter;
    file_put_contents($langPath . '/chat_moderation.php', $moderationContent);
    echo "✅ Creato chat_moderation.php con " . count($categories['moderation']) . " chiavi\n";
    $filesCreated++;
}

echo "\n📊 RIEPILOGO:\n";
echo "=============\n";
echo "File creati: $filesCreated\n";
echo "Chiavi totali distribuite: " . array_sum(array_map('count', $categories)) . "\n\n";

