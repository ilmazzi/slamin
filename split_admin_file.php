<?php

echo "📂 DIVISIONE FILE ADMIN.PHP\n";
echo "============================\n\n";

$langPath = 'lang/it';
$adminFile = $langPath . '/admin.php';

if (!file_exists($adminFile)) {
    echo "❌ File admin.php non trovato!\n";
    exit;
}

$content = file_get_contents($adminFile);
$lines = explode("\n", $content);

// Categorie per dividere le chiavi
$categories = [
    'general' => [],      // Admin generale
    'users' => [],        // Gestione utenti
    'content' => [],      // Gestione contenuti
    'system' => []        // Sistema e configurazioni
];

$phpHeader = "<?php\n\nreturn [\n";
$phpFooter = "\n];\n";

// Pattern per identificare le categorie
$categoryPatterns = [
    'users' => ['user', 'member', 'role', 'permission', 'ban', 'moderate'],
    'content' => ['article', 'poem', 'photo', 'video', 'media', 'post', 'comment'],
    'system' => ['setting', 'config', 'log', 'backup', 'database', 'cache']
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

// Admin generale
if (!empty($categories['general'])) {
    $generalContent = $phpHeader . implode("\n", $categories['general']) . $phpFooter;
    file_put_contents($langPath . '/admin_general.php', $generalContent);
    echo "✅ Creato admin_general.php con " . count($categories['general']) . " chiavi\n";
    $filesCreated++;
}

// Admin users
if (!empty($categories['users'])) {
    $usersContent = $phpHeader . implode("\n", $categories['users']) . $phpFooter;
    file_put_contents($langPath . '/admin_users.php', $usersContent);
    echo "✅ Creato admin_users.php con " . count($categories['users']) . " chiavi\n";
    $filesCreated++;
}

// Admin content
if (!empty($categories['content'])) {
    $contentContent = $phpHeader . implode("\n", $categories['content']) . $phpFooter;
    file_put_contents($langPath . '/admin_content.php', $contentContent);
    echo "✅ Creato admin_content.php con " . count($categories['content']) . " chiavi\n";
    $filesCreated++;
}

// Admin system
if (!empty($categories['system'])) {
    $systemContent = $phpHeader . implode("\n", $categories['system']) . $phpFooter;
    file_put_contents($langPath . '/admin_system.php', $systemContent);
    echo "✅ Creato admin_system.php con " . count($categories['system']) . " chiavi\n";
    $filesCreated++;
}

echo "\n📊 RIEPILOGO:\n";
echo "=============\n";
echo "File creati: $filesCreated\n";
echo "Chiavi totali distribuite: " . array_sum(array_map('count', $categories)) . "\n\n";

