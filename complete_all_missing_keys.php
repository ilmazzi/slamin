<?php
echo "🔧 COMPLETAMENTO TOTALE TUTTE LE CHIAVI MANCANTI\n";
echo "=================================================\n\n";

// Ottieni la lista completa dettagliata delle chiavi mancanti
$output = shell_exec('php find_missing_keys_simple.php 2>&1');
$lines = explode("\n", $output);

$missingByFile = [];
$currentFile = '';

foreach ($lines as $line) {
    if (strpos($line, '📄') !== false) {
        preg_match('/📄 ([^.]+)\.php \((\d+) chiavi/', $line, $matches);
        if (isset($matches[1])) {
            $currentFile = $matches[1];
            $missingByFile[$currentFile] = [];
        }
    } elseif (strpos($line, '   - ') === 0 && $currentFile) {
        $key = trim(str_replace('   - ', '', $line));
        if ($key && !strpos($key, '...')) {
            $missingByFile[$currentFile][] = $key;
        }
    }
}

echo "📊 File con chiavi mancanti:\n";
foreach ($missingByFile as $file => $keys) {
    echo "   - $file.php: " . count($keys) . " chiavi\n";
}
echo "\n";

$totalAdded = 0;

foreach ($missingByFile as $file => $keys) {
    if (empty($keys)) continue;
    
    $filePath = "lang/it/$file.php";
    
    if (!file_exists($filePath)) {
        echo "❌ File $filePath non trovato\n";
        continue;
    }
    
    echo "📄 Elaborando: $file.php (" . count($keys) . " chiavi da aggiungere)\n";
    
    $content = file_get_contents($filePath);
    $content = rtrim($content);
    $content = rtrim($content, '];');
    
    $added = 0;
    
    foreach ($keys as $fullKey) {
        $keyParts = explode('.', $fullKey);
        
        if (count($keyParts) >= 2) {
            $keyName = $keyParts[count($keyParts) - 1];
            
            // Controlla se la chiave esiste già
            if (strpos($content, "'$keyName'") === false && strpos($content, "\"$keyName\"") === false) {
                // Genera una traduzione sensata basata sul nome della chiave
                $translation = ucfirst(str_replace(['_', '-'], ' ', $keyName));
                
                // Traduzioni specifiche per chiavi comuni
                $commonTranslations = [
                    'actions' => 'Azioni',
                    'cancel' => 'Annulla',
                    'delete' => 'Elimina',
                    'edit' => 'Modifica',
                    'save' => 'Salva',
                    'create' => 'Crea',
                    'update' => 'Aggiorna',
                    'view' => 'Visualizza',
                    'back' => 'Indietro',
                    'next' => 'Avanti',
                    'previous' => 'Precedente',
                    'close' => 'Chiudi',
                    'open' => 'Apri',
                    'search' => 'Cerca',
                    'filter' => 'Filtra',
                    'sort' => 'Ordina',
                    'export' => 'Esporta',
                    'import' => 'Importa',
                    'download' => 'Scarica',
                    'upload' => 'Carica',
                    'title' => 'Titolo',
                    'description' => 'Descrizione',
                    'content' => 'Contenuto',
                    'status' => 'Stato',
                    'type' => 'Tipo',
                    'category' => 'Categoria',
                    'tags' => 'Tag',
                    'author' => 'Autore',
                    'date' => 'Data',
                    'time' => 'Ora',
                    'name' => 'Nome',
                    'email' => 'Email',
                    'password' => 'Password',
                    'confirm' => 'Conferma',
                    'yes' => 'Sì',
                    'no' => 'No',
                    'ok' => 'OK',
                    'error' => 'Errore',
                    'success' => 'Successo',
                    'warning' => 'Avviso',
                    'info' => 'Informazione',
                    'loading' => 'Caricamento',
                    'processing' => 'Elaborazione',
                    'pending' => 'In sospeso',
                    'approved' => 'Approvato',
                    'rejected' => 'Rifiutato',
                    'published' => 'Pubblicato',
                    'draft' => 'Bozza',
                    'private' => 'Privato',
                    'public' => 'Pubblico',
                    'active' => 'Attivo',
                    'inactive' => 'Inattivo',
                    'enabled' => 'Abilitato',
                    'disabled' => 'Disabilitato',
                    'all' => 'Tutti',
                    'none' => 'Nessuno',
                    'select' => 'Seleziona',
                    'choose' => 'Scegli',
                    'add' => 'Aggiungi',
                    'remove' => 'Rimuovi',
                    'clear' => 'Pulisci',
                    'reset' => 'Resetta',
                    'refresh' => 'Aggiorna',
                    'reload' => 'Ricarica',
                    'submit' => 'Invia',
                    'send' => 'Invia',
                    'reply' => 'Rispondi',
                    'forward' => 'Inoltra',
                    'share' => 'Condividi',
                    'like' => 'Mi piace',
                    'comment' => 'Commenta',
                    'comments' => 'Commenti',
                    'likes' => 'Mi piace',
                    'views' => 'Visualizzazioni',
                    'followers' => 'Follower',
                    'following' => 'Seguiti',
                    'follow' => 'Segui',
                    'unfollow' => 'Smetti di seguire',
                    'block' => 'Blocca',
                    'unblock' => 'Sblocca',
                    'report' => 'Segnala',
                    'flag' => 'Segnala',
                    'hide' => 'Nascondi',
                    'show' => 'Mostra',
                    'expand' => 'Espandi',
                    'collapse' => 'Comprimi',
                    'more' => 'Altro',
                    'less' => 'Meno',
                    'read more' => 'Leggi di più',
                    'show more' => 'Mostra di più',
                    'show less' => 'Mostra meno',
                    'load more' => 'Carica altro',
                    'see all' => 'Vedi tutti',
                    'view all' => 'Visualizza tutti',
                    'settings' => 'Impostazioni',
                    'preferences' => 'Preferenze',
                    'profile' => 'Profilo',
                    'account' => 'Account',
                    'dashboard' => 'Dashboard',
                    'home' => 'Home',
                    'logout' => 'Esci',
                    'login' => 'Accedi',
                    'register' => 'Registrati',
                    'signup' => 'Iscriviti',
                    'signin' => 'Accedi'
                ];
                
                if (isset($commonTranslations[$keyName])) {
                    $translation = $commonTranslations[$keyName];
                } elseif (isset($commonTranslations[str_replace('_', ' ', $keyName)])) {
                    $translation = $commonTranslations[str_replace('_', ' ', $keyName)];
                }
                
                $content .= "\n    '$keyName' => '$translation',";
                $added++;
            }
        }
    }
    
    if ($added > 0) {
        $content .= "\n\n];";
        file_put_contents($filePath, $content);
        echo "   ✅ Aggiunte $added chiavi\n";
        $totalAdded += $added;
    } else {
        echo "   ℹ️  Nessuna nuova chiave aggiunta\n";
    }
}

echo "\n🎉 COMPLETAMENTO TOTALE:\n";
echo "========================\n";
echo "Chiavi aggiunte: $totalAdded\n";
echo "File aggiornati: " . count(array_filter($missingByFile, function($keys) { return !empty($keys); })) . "\n\n";

echo "🔍 Verifica finale delle chiavi mancanti...\n";
