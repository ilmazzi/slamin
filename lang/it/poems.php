<?php

return [
    // Titoli e sezioni
    'title' => 'Poesie',
    'my_poems' => 'Le mie poesie',
    'create_poem' => 'Crea poesia',
    'edit_poem' => 'Modifica poesia',
    'poem_details' => 'Dettagli poesia',
    'poem_management' => 'Gestione poesie',
    'poem_moderation' => 'Moderazione poesie',
    
    // Form fields
    'title_label' => 'Titolo',
    'title_placeholder' => 'Inserisci il titolo della poesia',
    'content_label' => 'Contenuto',
    'content_placeholder' => 'Scrivi la tua poesia...',
    'description_label' => 'Descrizione',
    'description_placeholder' => 'Breve descrizione della poesia (opzionale)',
    'category_label' => 'Categoria',
    'category_placeholder' => 'Seleziona una categoria',
    'tags_label' => 'Tag',
    'tags_placeholder' => 'Inserisci i tag separati da virgole',
    'language_label' => 'Lingua',
    'poem_type_label' => 'Tipo di poesia',
    'thumbnail_label' => 'Immagine di copertina',
    'is_public_label' => 'Pubblica',
    'is_draft_label' => 'Salva come bozza',
    
    // Categorie
    'categories' => [
        'love' => 'Amore',
        'nature' => 'Natura',
        'social' => 'Sociale',
        'politics' => 'Politica',
        'personal' => 'Personale',
        'philosophy' => 'Filosofia',
        'religion' => 'Religione',
        'war' => 'Guerra',
        'peace' => 'Pace',
        'death' => 'Morte',
        'life' => 'Vita',
        'friendship' => 'Amicizia',
        'family' => 'Famiglia',
        'work' => 'Lavoro',
        'travel' => 'Viaggio',
        'other' => 'Altro'
    ],
    
    // Tipi di poesia
    'poem_types' => [
        'free_verse' => 'Verso libero',
        'sonnet' => 'Sonetto',
        'haiku' => 'Haiku',
        'limerick' => 'Limerick',
        'other' => 'Altro'
    ],
    
    // Stati di moderazione
    'moderation_status' => [
        'pending' => 'In attesa',
        'approved' => 'Approvata',
        'rejected' => 'Rifiutata'
    ],
    
    // Azioni
    'actions' => [
        'create' => 'Crea poesia',
        'edit' => 'Modifica',
        'delete' => 'Elimina',
        'publish' => 'Pubblica',
        'unpublish' => 'Rimuovi dalla pubblicazione',
        'save_draft' => 'Salva bozza',
        'approve' => 'Approva',
        'reject' => 'Rifiuta',
        'feature' => 'Metti in evidenza',
        'unfeature' => 'Rimuovi dall\'evidenza',
        'like' => 'Mi piace',
        'unlike' => 'Non mi piace più',
        'bookmark' => 'Segnalibro',
        'unbookmark' => 'Rimuovi dai segnalibri',
        'share' => 'Condividi',
        'comment' => 'Commenta',
        'reply' => 'Rispondi',
        'translate' => 'Traduci',
        'request_translation' => 'Richiedi traduzione',
        'read' => 'Leggi'
    ],
    
    // Messaggi
    'messages' => [
        'created' => 'Poesia creata con successo!',
        'updated' => 'Poesia aggiornata con successo!',
        'deleted' => 'Poesia eliminata con successo!',
        'published' => 'Poesia pubblicata con successo!',
        'unpublished' => 'Poesia rimossa dalla pubblicazione!',
        'draft_saved' => 'Bozza salvata con successo!',
        'approved' => 'Poesia approvata!',
        'rejected' => 'Poesia rifiutata!',
        'featured' => 'Poesia messa in evidenza!',
        'unfeatured' => 'Poesia rimossa dall\'evidenza!',
        'liked' => 'Poesia aggiunta ai preferiti!',
        'unliked' => 'Poesia rimossa dai preferiti!',
        'bookmarked' => 'Poesia aggiunta ai segnalibri!',
        'unbookmarked' => 'Poesia rimossa dai segnalibri!',
        'shared' => 'Poesia condivisa con successo!',
        'commented' => 'Commento aggiunto con successo!',
        'translation_requested' => 'Richiesta di traduzione inviata!',
        'translation_created' => 'Traduzione creata con successo!'
    ],
    
    // Errori
    'errors' => [
        'not_found' => 'Poesia non trovata!',
        'not_authorized' => 'Non sei autorizzato a eseguire questa azione!',
        'already_liked' => 'Hai già messo mi piace a questa poesia!',
        'already_bookmarked' => 'Hai già aggiunto questa poesia ai segnalibri!',
        'translation_not_available' => 'Traduzione non disponibile per questa poesia!',
        'translation_already_exists' => 'Traduzione già esistente!',
        'invalid_file' => 'File non valido!',
        'file_too_large' => 'File troppo grande!'
    ],
    
    // Statistiche
    'stats' => [
        'views' => 'Visualizzazioni',
        'likes' => 'Mi piace',
        'comments' => 'Commenti',
        'shares' => 'Condivisioni',
        'bookmarks' => 'Segnalibri',
        'word_count' => 'Parole',
        'reading_time' => 'Tempo di lettura'
    ],
    
    // Traduzione
    'translation' => [
        'title' => 'Traduzione',
        'original' => 'Originale',
        'translated' => 'Tradotto',
        'request_translation' => 'Richiedi traduzione',
        'translation_price' => 'Prezzo traduzione',
        'translation_available' => 'Traduzione disponibile',
        'translation_not_available' => 'Traduzione non disponibile',
        'translation_requests' => 'Richieste di traduzione',
        'translate_this_poem' => 'Traduci questa poesia',
        'translation_instructions' => 'Inserisci la traduzione della poesia',
        'target_language' => 'Lingua di destinazione',
        'translation_notes' => 'Note sulla traduzione (opzionale)',
        'submit_translation' => 'Invia traduzione',
        'translation_submitted' => 'Traduzione inviata con successo!',
        'translation_approved' => 'Traduzione approvata!',
        'translation_rejected' => 'Traduzione rifiutata!'
    ],
    
    // Filtri e ricerca
    'filters' => [
        'all' => 'Tutte',
        'recent' => 'Recenti',
        'popular' => 'Popolari',
        'featured' => 'In evidenza',
        'my_poems' => 'Le mie poesie',
        'drafts' => 'Bozze',
        'pending' => 'In attesa',
        'approved' => 'Approvate',
        'rejected' => 'Rifiutate',
        'search_placeholder' => 'Cerca poesie...',
        'filter_by_category' => 'Filtra per categoria',
        'filter_by_language' => 'Filtra per lingua',
        'filter_by_type' => 'Filtra per tipo',
        'sort_by' => 'Ordina per',
        'sort_options' => [
            'newest' => 'Più recenti',
            'oldest' => 'Più vecchie',
            'most_viewed' => 'Più visualizzate',
            'most_liked' => 'Più apprezzate',
            'most_commented' => 'Più commentate',
            'alphabetical' => 'Alfabetico'
        ]
    ],
    
    // Paginazione
    'pagination' => [
        'showing' => 'Mostrando',
        'to' => 'a',
        'of' => 'di',
        'results' => 'risultati',
        'per_page' => 'per pagina'
    ],
    
    // Conferme
    'confirmations' => [
        'delete' => 'Sei sicuro di voler eliminare questa poesia?',
        'publish' => 'Sei sicuro di voler pubblicare questa poesia?',
        'unpublish' => 'Sei sicuro di voler rimuovere questa poesia dalla pubblicazione?',
        'approve' => 'Sei sicuro di voler approvare questa poesia?',
        'reject' => 'Sei sicuro di voler rifiutare questa poesia?',
        'feature' => 'Sei sicuro di voler mettere in evidenza questa poesia?',
        'unfeature' => 'Sei sicuro di voler rimuovere dall\'evidenza questa poesia?'
    ],
    
    // Placeholder e helper
    'placeholders' => [
        'title' => 'Inserisci un titolo accattivante...',
        'content' => 'Scrivi la tua poesia qui...',
        'description' => 'Breve descrizione della poesia...',
        'tags' => 'amore, natura, vita...',
        'search' => 'Cerca per titolo, contenuto, autore...'
    ],

    // Messaggi di stato vuoto
    'no_poems_found' => 'Nessuna poesia trovata',
    'no_poems_description' => 'Non ci sono poesie che corrispondono ai tuoi criteri di ricerca.',
    'no_poems_yet' => 'Non hai ancora creato nessuna poesia',
    'no_poems_yet_description' => 'Inizia a scrivere la tua prima poesia!',
    'no_drafts_yet' => 'Non hai ancora bozze salvate',
    'no_drafts_yet_description' => 'Le tue bozze appariranno qui.',
    'no_bookmarks_yet' => 'Non hai ancora salvato nessuna poesia',
    'no_bookmarks_yet_description' => 'Le poesie che aggiungi ai segnalibri appariranno qui.',
    'no_liked_poems_yet' => 'Non hai ancora messo mi piace a nessuna poesia',
    'no_liked_poems_yet_description' => 'Le poesie che ti piacciono appariranno qui.',
    
    // Tooltip
    'tooltips' => [
        'like' => 'Metti mi piace a questa poesia',
        'unlike' => 'Rimuovi il mi piace',
        'bookmark' => 'Aggiungi ai segnalibri',
        'unbookmark' => 'Rimuovi dai segnalibri',
        'share' => 'Condividi questa poesia',
        'comment' => 'Aggiungi un commento',
        'edit' => 'Modifica questa poesia',
        'delete' => 'Elimina questa poesia',
        'translate' => 'Traduci questa poesia',
        'feature' => 'Metti in evidenza',
        'unfeature' => 'Rimuovi dall\'evidenza'
    ]
]; 