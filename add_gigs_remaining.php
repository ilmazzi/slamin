<?php
$gigsKeys = [
    'about_author' => 'Informazioni autore', 'actions' => ['apply' => 'Candidati', 'close_gig' => 'Chiudi gig', 'confirm_close' => 'Conferma chiusura', 'confirm_close_text' => 'Sei sicuro di voler chiudere questo gig?', 'confirm_reopen' => 'Conferma riapertura', 'confirm_reopen_text' => 'Sei sicuro di voler riaprire questo gig?', 'message' => 'Messaggio', 'message_placeholder' => 'Scrivi il tuo messaggio...', 'read' => 'Leggi', 'reopen' => 'Riapri'], 'applications' => 'Candidature', 'cancel' => 'Annulla', 'close' => 'Chiudi', 'confirm_delete' => 'Conferma eliminazione', 'create_gig' => 'Crea gig', 'delete' => 'Elimina', 'description' => 'Descrizione', 'edit' => 'Modifica', 'gigs' => 'Gig', 'no_gigs_found' => 'Nessun gig trovato', 'save' => 'Salva', 'status' => 'Stato', 'title' => 'Titolo', 'view' => 'Visualizza'
];
$filePath = "lang/it/gigs.php";
$content = file_get_contents($filePath);
$content = rtrim($content, '];');
$added = 0;
foreach ($gigsKeys as $key => $value) {
    if (is_array($value)) {
        if (strpos($content, "'$key'") === false) {
            $content .= "\n    '$key' => [";
            foreach ($value as $subKey => $subValue) {
                $content .= "\n        '$subKey' => '$subValue',";
            }
            $content .= "\n    ],";
            $added += count($value);
        }
    } else {
        if (strpos($content, "'$key'") === false && strpos($content, "\"$key\"") === false) {
            $content .= "\n    '$key' => '$value',";
            $added++;
        }
    }
}
$content .= "\n\n];";
file_put_contents($filePath, $content);
echo "✅ Aggiunte $added chiavi a gigs.php\n";
