<?php

echo "🔍 VERIFICA CHIAVI MANCANTI\n";
echo "===========================\n\n";

// Controlla le chiavi specifiche che abbiamo visto
$keysToCheck = [
    'home.carousel.previous',
    'home.carousel.next', 
    'home.upcoming_events',
    'home.details',
    'home.new_videos',
    'home.stats.total_videos',
    'home.stats.total_views',
    'wishlist.remove_from_wishlist',
    'auth.login_required'
];

$missingKeys = [];
$existingKeys = [];

foreach ($keysToCheck as $key) {
    $keyParts = explode('.', $key);
    $file = $keyParts[0];
    $keyName = $keyParts[1];
    $translationFile = "lang/it/$file.php";
    
    echo "🔍 Verificando: $key\n";
    
    if (!file_exists($translationFile)) {
        echo "   ❌ File mancante: $translationFile\n";
        $missingKeys[] = $key;
    } else {
        $translationContent = file_get_contents($translationFile);
        if (!preg_match("/[\'\"]({$keyName})[\'"]\s*=>/", $translationContent)) {
            echo "   ❌ Chiave mancante: $keyName in $file.php\n";
            $missingKeys[] = $key;
        } else {
            echo "   ✅ Chiave trovata\n";
            $existingKeys[] = $key;
        }
    }
    echo "\n";
}

echo "📊 RIEPILOGO:\n";
echo "=============\n";
echo "Chiavi esistenti: " . count($existingKeys) . "\n";
echo "Chiavi mancanti: " . count($missingKeys) . "\n\n";

if (count($missingKeys) > 0) {
    echo "🚨 CHIAVI MANCANTI:\n";
    echo "===================\n";
    foreach ($missingKeys as $key) {
        echo "   - $key\n";
    }
} else {
    echo "✅ TUTTE LE CHIAVI ESISTONO!\n";
}

