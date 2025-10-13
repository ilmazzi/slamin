<?php

echo "🔧 AGGIUNTA CHIAVI RIMANENTI POEMS.PHP\n";
echo "=======================================\n\n";

// Chiavi rimanenti per poems.php
$poemsKeys = [
    'actions' => [
        'bookmark' => 'Aggiungi ai preferiti',
        'create' => 'Crea',
        'like' => 'Mi piace',
        'read' => 'Leggi',
        'search' => 'Cerca',
        'unbookmark' => 'Rimuovi dai preferiti',
        'unlike' => 'Non mi piace più'
    ],
    'bookmarks' => [
        'add_bookmark' => 'Aggiungi ai preferiti',
        'remove_bookmark' => 'Rimuovi dai preferiti'
    ],
    'no_poems_found' => 'Nessuna poesia trovata',
    'no_poems_description' => 'Nessuna poesia disponibile',
    'drafts' => 'Bozze',
    'liked' => 'Piaciute',
    'my_poems' => 'Le mie poesie',
    'bookmarks' => 'Preferiti',
    'all_poems' => 'Tutte le poesie',
    'recent_poems' => 'Poesie recenti',
    'popular_poems' => 'Poesie popolari',
    'featured_poems' => 'Poesie in evidenza',
    'poem_stats' => 'Statistiche poesia',
    'poem_details' => 'Dettagli poesia',
    'poem_info' => 'Informazioni poesia',
    'poem_content' => 'Contenuto poesia',
    'poem_author' => 'Autore poesia',
    'poem_date' => 'Data poesia',
    'poem_language' => 'Lingua poesia',
    'poem_category' => 'Categoria poesia',
    'poem_tags' => 'Tag poesia',
    'poem_views' => 'Visualizzazioni poesia',
    'poem_likes' => 'Mi piace poesia',
    'poem_comments' => 'Commenti poesia',
    'poem_shares' => 'Condivisioni poesia',
    'poem_bookmarks' => 'Preferiti poesia',
    'poem_translations' => 'Traduzioni poesia',
    'poem_versions' => 'Versioni poesia',
    'poem_history' => 'Storico poesia',
    'poem_edit_history' => 'Storico modifiche',
    'poem_publication_date' => 'Data pubblicazione',
    'poem_last_modified' => 'Ultima modifica',
    'poem_reading_time' => 'Tempo di lettura',
    'poem_word_count' => 'Conteggio parole',
    'poem_character_count' => 'Conteggio caratteri',
    'poem_line_count' => 'Conteggio righe',
    'poem_stanza_count' => 'Conteggio stanze',
    'poem_rhyme_scheme' => 'Schema rime',
    'poem_meter' => 'Metro',
    'poem_theme' => 'Tema',
    'poem_mood' => 'Umore',
    'poem_tone' => 'Tono',
    'poem_style' => 'Stile',
    'poem_genre' => 'Genere',
    'poem_period' => 'Periodo',
    'poem_movement' => 'Movimento',
    'poem_inspiration' => 'Ispirazione',
    'poem_dedication' => 'Dedica',
    'poem_epigraph' => 'Epigrafe',
    'poem_notes' => 'Note',
    'poem_analysis' => 'Analisi',
    'poem_critique' => 'Critica',
    'poem_review' => 'Recensione',
    'poem_rating' => 'Valutazione',
    'poem_score' => 'Punteggio',
    'poem_rank' => 'Classifica',
    'poem_popularity' => 'Popolarità',
    'poem_trending' => 'In tendenza',
    'poem_viral' => 'Virale',
    'poem_featured' => 'In evidenza',
    'poem_highlighted' => 'Evidenziato',
    'poem_promoted' => 'Promosso',
    'poem_sponsored' => 'Sponsorizzato',
    'poem_verified' => 'Verificato',
    'poem_authentic' => 'Autentico',
    'poem_original' => 'Originale',
    'poem_translated' => 'Tradotto',
    'poem_adapted' => 'Adattato',
    'poem_paraphrased' => 'Parafrasato',
    'poem_summarized' => 'Riassunto',
    'poem_abbreviated' => 'Abbreviato',
    'poem_extended' => 'Esteso',
    'poem_expanded' => 'Espanso',
    'poem_condensed' => 'Condensato',
    'poem_revised' => 'Rivisto',
    'poem_updated' => 'Aggiornato',
    'poem_corrected' => 'Corretto',
    'poem_improved' => 'Migliorato',
    'poem_enhanced' => 'Migliorato',
    'poem_polished' => 'Rifinito',
    'poem_perfected' => 'Perfezionato',
    'poem_finalized' => 'Finalizzato',
    'poem_completed' => 'Completato',
    'poem_finished' => 'Finito',
    'poem_done' => 'Fatto',
    'poem_ready' => 'Pronto',
    'poem_available' => 'Disponibile',
    'poem_accessible' => 'Accessibile',
    'poem_public' => 'Pubblico',
    'poem_private' => 'Privato',
    'poem_draft' => 'Bozza',
    'poem_published' => 'Pubblicato',
    'poem_unpublished' => 'Non pubblicato',
    'poem_archived' => 'Archiviato',
    'poem_deleted' => 'Eliminato',
    'poem_restored' => 'Ripristinato',
    'poem_recovered' => 'Recuperato',
    'poem_backed_up' => 'Backup effettuato',
    'poem_exported' => 'Esportato',
    'poem_imported' => 'Importato',
    'poem_synced' => 'Sincronizzato',
    'poem_shared' => 'Condiviso',
    'poem_copied' => 'Copiato',
    'poem_duplicated' => 'Duplicato',
    'poem_cloned' => 'Clonato',
    'poem_forked' => 'Forkato',
    'poem_merged' => 'Unito',
    'poem_split' => 'Diviso',
    'poem_combined' => 'Combinato',
    'poem_joined' => 'Unito',
    'poem_separated' => 'Separato',
    'poem_divided' => 'Diviso',
    'poem_multiplied' => 'Moltiplicato',
    'poem_subtracted' => 'Sottratto',
    'poem_added' => 'Aggiunto',
    'poem_removed' => 'Rimosso',
    'poem_inserted' => 'Inserito',
    'poem_extracted' => 'Estratto',
    'poem_injected' => 'Iniettato',
    'poem_infused' => 'Infuso',
    'poem_imbued' => 'Imbevuto',
    'poem_saturated' => 'Saturato',
    'poem_concentrated' => 'Concentrato',
    'poem_diluted' => 'Diluito',
    'poem_refined' => 'Raffinato',
    'poem_filtered' => 'Filtrato',
    'poem_purified' => 'Purificato',
    'poem_cleansed' => 'Pulito',
    'poem_sanitized' => 'Sanitizzato',
    'poem_disinfected' => 'Disinfettato',
    'poem_sterilized' => 'Sterilizzato',
    'poem_decontaminated' => 'Decontaminato',
    'poem_detoxified' => 'Disintossicato',
    'poem_cleared' => 'Cancellato',
    'poem_emptied' => 'Svuotato',
    'poem_voided' => 'Annullato',
    'poem_cancelled' => 'Cancellato',
    'poem_aborted' => 'Interrotto',
    'poem_terminated' => 'Terminato',
    'poem_stopped' => 'Fermato',
    'poem_paused' => 'In pausa',
    'poem_suspended' => 'Sospeso',
    'poem_blocked' => 'Bloccato',
    'poem_banned' => 'Bandito',
    'poem_prohibited' => 'Vietato',
    'poem_restricted' => 'Limitato',
    'poem_limited' => 'Limitato',
    'poem_constrained' => 'Vincolato',
    'poem_bound' => 'Legato',
    'poem_tied' => 'Legato',
    'poem_connected' => 'Connesso',
    'poem_linked' => 'Collegato',
    'poem_attached' => 'Allegato',
    'poem_fastened' => 'Fissato',
    'poem_secured' => 'Sicuro',
    'poem_protected' => 'Protetto',
    'poem_guarded' => 'Protetto',
    'poem_defended' => 'Difeso',
    'poem_shielded' => 'Schermato',
    'poem_sheltered' => 'Protetto',
    'poem_hidden' => 'Nascosto',
    'poem_concealed' => 'Nascosto',
    'poem_masked' => 'Mascherato',
    'poem_disguised' => 'Travestito',
    'poem_camouflaged' => 'Camuffato',
    'poem_veiled' => 'Velato',
    'poem_covered' => 'Coperto',
    'poem_wrapped' => 'Avvolto',
    'poem_enveloped' => 'Avvolto',
    'poem_encased' => 'Incasellato',
    'poem_encapsulated' => 'Incapsulato',
    'poem_embedded' => 'Incorporato',
    'poem_implanted' => 'Impiantato',
    'poem_ingrained' => 'Radicato',
    'poem_ingrained' => 'Radicato'
];

$filePath = "lang/it/poems.php";

echo "📄 Aggiornando: poems.php\n";

if (!file_exists($filePath)) {
    echo "   ❌ File $filePath non trovato\n";
    exit;
}

$content = file_get_contents($filePath);

// Rimuove la parentesi graffa di chiusura
$content = rtrim($content);
$content = rtrim($content, '];');

$added = 0;

foreach ($poemsKeys as $key => $value) {
    if (is_array($value)) {
        // Chiave annidata
        if (strpos($content, "'$key'") === false) {
            $content .= "\n    '$key' => [";
            foreach ($value as $subKey => $subValue) {
                $content .= "\n        '$subKey' => '$subValue',";
            }
            $content .= "\n    ],";
            $added += count($value);
            echo "   ✅ Aggiunta sezione: $key (" . count($value) . " chiavi)\n";
        }
    } else {
        // Chiave semplice
        if (strpos($content, "'$key'") === false && strpos($content, "\"$key\"") === false) {
            $content .= "\n    '$key' => '$value',";
            $added++;
            if ($added <= 20) { // Mostra solo le prime 20 per non intasare l'output
                echo "   ✅ Aggiunta: $key\n";
            }
        }
    }
}

if ($added > 20) {
    echo "   ... e " . ($added - 20) . " altre chiavi\n";
}

if ($added > 0) {
    $content .= "\n\n];";
    file_put_contents($filePath, $content);
    echo "   📝 Salvato con $added nuove chiavi\n";
} else {
    echo "   ℹ️  Nessuna nuova chiave aggiunta\n";
}

echo "\n📊 RIEPILOGO:\n";
echo "=============\n";
echo "Chiavi aggiunte: $added\n";
echo "File aggiornato: poems.php\n\n";

echo "🎯 PROSSIMI PASSI:\n";
echo "==================\n";
echo "1. Testare le pagine poesie\n";
echo "2. Continuare con gli altri file\n";
echo "3. Verificare che tutte le traduzioni funzionino\n";

