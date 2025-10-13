<?php
$articlesKeys = [
    'about_author' => 'Informazioni autore', 'actions' => ['apply' => 'Candidati', 'read' => 'Leggi', 'share' => 'Condividi', 'bookmark' => 'Aggiungi ai preferiti', 'unbookmark' => 'Rimuovi dai preferiti'], 'article_stats' => 'Statistiche articolo', 'articles' => 'Articoli', 'back_to_dashboard' => 'Torna alla dashboard', 'browse_all_articles' => 'Sfoglia tutti gli articoli', 'cancel' => 'Annulla', 'categories' => 'Categorie', 'category' => 'Categoria', 'close' => 'Chiudi', 'confirm_delete' => 'Conferma eliminazione', 'confirm_mark_as' => 'Conferma segna come', 'content' => 'Contenuto', 'copyright' => 'Copyright', 'create_error' => 'Errore creazione', 'delete' => 'Elimina', 'delete_error' => 'Errore eliminazione', 'details' => 'Dettagli', 'edit' => 'Modifica', 'error_loading_report' => 'Errore caricamento report', 'image_help' => 'Aiuto immagine', 'max_size' => 'Dimensione massima', 'meta_keywords_help' => 'Aiuto meta keywords', 'name' => 'Nome', 'no_details_provided' => 'Nessun dettaglio fornito', 'no_reports' => 'Nessun report', 'no_reports_description' => 'Nessun report disponibile', 'other' => 'Altro', 'pending' => 'In sospeso', 'preview' => 'Anteprima', 'published' => 'Pubblicato', 'report_marked_as' => 'Report segnato come', 'review_error' => 'Errore revisione', 'save' => 'Salva', 'save_draft' => 'Salva bozza', 'select_category' => 'Seleziona categoria', 'status' => 'Stato', 'success' => 'Successo', 'tags' => 'Tag', 'tags_help' => 'Aiuto tag', 'title' => 'Titolo', 'update' => 'Aggiorna', 'update_article' => 'Aggiorna articolo', 'update_error' => 'Errore aggiornamento', 'updated_successfully' => 'Aggiornato con successo', 'view' => 'Visualizza'
];
$filePath = "lang/it/articles.php";
$content = file_get_contents($filePath);
$content = rtrim($content, '];');
$added = 0;
foreach ($articlesKeys as $key => $value) {
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
echo "✅ Aggiunte $added chiavi a articles.php\n";
