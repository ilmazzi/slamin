<?php

echo "📂 DIVISIONE FILE EVENTS.PHP\n";
echo "============================\n\n";

$langPath = 'lang/it';
$eventsFile = $langPath . '/events.php';

if (!file_exists($eventsFile)) {
    echo "❌ File events.php non trovato!\n";
    exit;
}

$content = file_get_contents($eventsFile);
$lines = explode("\n", $content);

// Categorie per dividere le chiavi
$categories = [
    'general' => [],      // Chiavi generali eventi
    'management' => [],   // Gestione eventi (admin)
    'scoring' => [],      // Sistema punteggi
    'gamification' => []  // Badge e gamification
];

$currentCategory = 'general';
$phpHeader = "<?php\n\nreturn [\n";
$phpFooter = "\n];\n";

// Pattern per identificare le categorie
$categoryPatterns = [
    'management' => ['admin', 'manage', 'invite', 'participant', 'organizer', 'moderate'],
    'scoring' => ['score', 'judge', 'round', 'ranking', 'points', 'calculate'],
    'gamification' => ['badge', 'level', 'achievement', 'leaderboard', 'medal']
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

// Events generale
if (!empty($categories['general'])) {
    $generalContent = $phpHeader . implode("\n", $categories['general']) . $phpFooter;
    file_put_contents($langPath . '/events_general.php', $generalContent);
    echo "✅ Creato events_general.php con " . count($categories['general']) . " chiavi\n";
    $filesCreated++;
}

// Events management
if (!empty($categories['management'])) {
    $managementContent = $phpHeader . implode("\n", $categories['management']) . $phpFooter;
    file_put_contents($langPath . '/events_management.php', $managementContent);
    echo "✅ Creato events_management.php con " . count($categories['management']) . " chiavi\n";
    $filesCreated++;
}

// Events scoring
if (!empty($categories['scoring'])) {
    $scoringContent = $phpHeader . implode("\n", $categories['scoring']) . $phpFooter;
    file_put_contents($langPath . '/events_scoring.php', $scoringContent);
    echo "✅ Creato events_scoring.php con " . count($categories['scoring']) . " chiavi\n";
    $filesCreated++;
}

// Events gamification
if (!empty($categories['gamification'])) {
    $gamificationContent = $phpHeader . implode("\n", $categories['gamification']) . $phpFooter;
    file_put_contents($langPath . '/events_gamification.php', $gamificationContent);
    echo "✅ Creato events_gamification.php con " . count($categories['gamification']) . " chiavi\n";
    $filesCreated++;
}

echo "\n📊 RIEPILOGO:\n";
echo "=============\n";
echo "File creati: $filesCreated\n";
echo "Chiavi totali distribuite: " . array_sum(array_map('count', $categories)) . "\n\n";

echo "🎯 PROSSIMO STEP:\n";
echo "=================\n";
echo "Ora possiamo eliminare il file events.php originale\n";
echo "e aggiornare i riferimenti nel codice\n";

