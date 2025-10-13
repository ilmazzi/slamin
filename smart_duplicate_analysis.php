<?php

// Analisi intelligente dei duplicati per decidere cosa mantenere
echo "🧠 ANALISI INTELLIGENTE DUPLICATI\n";
echo "=================================\n\n";

// Chiavi che dovrebbero essere SOLO in common.php
$commonOnlyKeys = [
    'delete', 'view', 'edit', 'cancel', 'save', 'close', 'ok',
    'actions', 'status', 'preview', 'search_placeholder'
];

// Chiavi che possono avere significati diversi per contesto
$contextSpecificKeys = [
    'title' => [
        'common' => 'Titolo generico',
        'articles' => 'Titolo articolo', 
        'events' => 'Titolo evento',
        'photos' => 'Titolo foto'
    ],
    'content' => [
        'common' => 'Contenuto generico',
        'articles' => 'Contenuto articolo',
        'forum' => 'Contenuto post'
    ],
    'comments' => [
        'common' => 'Commenti generici',
        'articles' => 'Commenti articolo',
        'forum' => 'Commenti forum'
    ]
];

echo "📋 CHIAVI DA SPOSTARE IN COMMON.PHP:\n";
echo "===================================\n";
foreach ($commonOnlyKeys as $key) {
    echo "✅ '$key' → common.php\n";
}

echo "\n📋 CHIAVI CONTESTO-SPECIFICHE:\n";
echo "===============================\n";
foreach ($contextSpecificKeys as $key => $contexts) {
    echo "🔑 '$key':\n";
    foreach ($contexts as $file => $description) {
        echo "   - $file.php: $description\n";
    }
    echo "\n";
}

echo "🎯 STRATEGIA DI PULIZIA:\n";
echo "=======================\n";
echo "1. Sposta chiavi universali in common.php\n";
echo "2. Rinomina chiavi contesto-specifiche (es: article_title, event_title)\n";
echo "3. Mantieni solo una versione per ogni significato\n";
echo "4. Aggiorna tutti i riferimenti nel codice\n";

