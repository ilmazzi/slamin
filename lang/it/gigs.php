<?php

return [
    // Titoli principali
    'title' => 'Ingaggi',
    'gigs' => 'Ingaggi',
    'my_gigs' => 'I Miei Ingaggi',
    'all_gigs' => 'Tutti gli Ingaggi',
    'open_gigs' => 'Ingaggi Aperti',
    'closed_gigs' => 'Ingaggi Chiusi',

    // Azioni principali
    'create_gig' => 'Crea Ingaggio',
    'edit_gig' => 'Modifica Ingaggio',
    'manage_gigs' => 'Gestisci Ingaggi',
    'apply_gig' => 'Candidati',
    'close_gig' => 'Chiudi Ingaggio',
    'reopen_gig' => 'Riapri Ingaggio',
    'delete_gig' => 'Elimina Ingaggio',

    // Campi del form
    'fields' => [
        'title' => 'Titolo Posizione',
        'description' => 'Descrizione',
        'requirements' => 'Requisiti',
        'compensation' => 'Compenso',
        'deadline' => 'Scadenza Candidature',
        'event' => 'Evento',
        'group' => 'Gruppo',
        'category' => 'Categoria',
        'type' => 'Tipo di Ingaggio',
        'language' => 'Lingua',
        'location' => 'Località',
        'is_remote' => 'Lavoro Remoto',
        'is_urgent' => 'Urgente',
        'is_featured' => 'In Evidenza',
        'max_applications' => 'Numero Massimo Candidature',
        'allow_group_admin_edit' => 'Permetti Modifica Admin Gruppo',
    ],

    // Tipi di ingaggio
    'types' => [
        'artist_poet' => 'Artista/Poeta',
        'mc_guest' => 'MC/Ospite',
        'technical_support' => 'Supporto Tecnico',
        'volunteer' => 'Volontaria/Volontario',
    ],

    // Categorie
    'categories' => [
        'poetry_slam' => 'Poetry Slam',
        'theater' => 'Teatro',
        'music' => 'Musica',
        'dance' => 'Danza',
        'comedy' => 'Commedia',
        'workshop' => 'Workshop',
        'festival' => 'Festival',
        'conference' => 'Conferenza',
        'exhibition' => 'Mostra',
        'competition' => 'Competizione',
        'other' => 'Altro',
    ],

    // Stati
    'status' => [
        'title' => 'Stato',
        'open' => 'Aperto',
        'closed' => 'Chiuso',
        'urgent' => 'Urgente',
        'featured' => 'In Evidenza',
        'expired' => 'Scaduto',
        'filled' => 'Coperto',
        'cancelled' => 'Annullato',
    ],

    // Filtri e ricerca
    'filters' => [
        'title' => 'Filtri e Ricerca',
        'search' => 'Cerca ingaggi...',
        'filter_by_category' => 'Filtra per categoria',
        'filter_by_type' => 'Filtra per tipo',
        'filter_by_location' => 'Filtra per località',
        'filter_by_status' => 'Filtra per stato',
        'sort_by' => 'Ordina per',
        'sort_options' => [
            'recent' => 'Più recenti',
            'deadline' => 'Scadenza',
            'urgent' => 'Urgenti',
            'featured' => 'In evidenza',
            'compensation' => 'Compenso',
            'applications' => 'Numero candidature',
        ],
        'select_category' => 'Seleziona categoria',
        'select_type' => 'Seleziona tipo',
        'select_language' => 'Seleziona lingua',
        'select_event' => 'Seleziona evento',
        'select_group' => 'Seleziona gruppo',
        'show_remote' => 'Solo remoto',
        'show_urgent' => 'Solo urgenti',
        'show_featured' => 'Solo in evidenza',
    ],



    // Gestione
    'management' => [
        'title' => 'Gestione Ingaggi',
        'manage_applications' => 'Gestisci Candidature',
        'view_applications' => 'Visualizza Candidature',
        'send_global_message' => 'Invia Messaggio Globale',
        'global_message_placeholder' => 'Scrivi un messaggio per tutti i professionisti...',
        'send_message' => 'Invia Messaggio',
        'message_sent' => 'Messaggio inviato con successo!',
        'extend_deadline' => 'Estendi Scadenza',
        'new_deadline' => 'Nuova Scadenza',
        'deadline_extended' => 'Scadenza estesa con successo!',
        'close_applications' => 'Chiudi Candidature',
        'reopen_applications' => 'Riapri Candidature',
        'close_confirm' => 'Sei sicuro di voler chiudere le candidature?',
        'reopen_confirm' => 'Sei sicuro di voler riaprire le candidature?',
        'delete_confirm' => 'Sei sicuro di voler eliminare questo ingaggio?',
        'allow_group_admin_edit_help' => 'Permetti agli admin del gruppo di modificare questo ingaggio',
    ],

    // Messaggi e notifiche
    'messages' => [
        'no_gigs_found' => 'Nessun ingaggio trovato',
        'no_gigs_description' => 'Non ci sono ingaggi che corrispondono ai tuoi criteri di ricerca.',
        'no_applications' => 'Nessuna candidatura ricevuta',
        'no_applications_description' => 'Non hai ancora ricevuto candidature per questo ingaggio.',
        'no_my_applications' => 'Non hai ancora inviato candidature',
        'no_my_applications_description' => 'Inizia a cercare ingaggi e invia le tue candidature.',
        'gig_created' => 'Ingaggio creato con successo!',
        'gig_updated' => 'Ingaggio aggiornato con successo!',
        'gig_deleted' => 'Ingaggio eliminato con successo!',
        'gig_closed' => 'Ingaggio chiuso con successo!',
        'gig_reopened' => 'Ingaggio riaperto con successo!',
        'application_accepted' => 'Candidatura accettata!',
        'application_rejected' => 'Candidatura rifiutata',
        'application_sent' => 'Candidatura inviata con successo!',
        'gig_shared' => 'Ingaggio condiviso con :count utenti!',
        'no_my_gigs' => 'Non hai ancora creato ingaggi',
        'no_my_gigs_description' => 'Inizia a creare ingaggi per i tuoi eventi e trova i professionisti giusti.',
        'confirm_delete' => 'Sei sicuro di voler eliminare questo ingaggio?',
        'delete_error' => 'Errore durante l\'eliminazione dell\'ingaggio',
        'login_to_apply' => 'Accedi per candidarti agli ingaggi',
        'login_to_interact' => 'Accedi per interagire',
        'audience_not_allowed' => 'Gli utenti audience non possono accedere agli ingaggi',
    ],

    // Statistiche
    'stats' => [
        'total_gigs' => 'Ingaggi Totali',
        'open_gigs_count' => 'Ingaggi Aperti',
        'urgent_gigs_count' => 'Ingaggi Urgenti',
        'total_applications' => 'Candidature Totali',
        'applications' => 'Candidature',
        'pending_applications_count' => 'Candidature in Attesa',
        'accepted_applications_count' => 'Candidature Accettate',
        'my_applications_count' => 'Le Mie Candidature',
        'my_gigs_count' => 'I Miei Ingaggi',
        'browse_all' => 'Sfoglia Tutti',
    ],

    // Placeholder e help
    'placeholders' => [
        'title' => 'Es: Performer Poetry Slam per Evento Estivo',
        'description' => 'Descrivi dettagliatamente la posizione, le responsabilità e le aspettative...',
        'requirements' => 'Specifica i requisiti, competenze e esperienze richieste...',
        'compensation' => 'Specifica il compenso offerto (es: €200, gratuito, percentuale...)',
        'location' => 'Città, regione o "Remoto"',
        'deadline' => 'Seleziona data e ora di scadenza',
        'max_applications' => 'Numero massimo di candidature accettate',
    ],

    'help' => [
        'title' => 'Sii specifico e accattivante nel titolo',
        'description' => 'Fornisci tutti i dettagli necessari per attirare i candidati giusti',
        'requirements' => 'Sii chiaro sui requisiti per evitare candidature inappropriate',
        'compensation' => 'Specifica chiaramente il compenso per attirare candidati qualificati',
        'deadline' => 'Imposta una scadenza ragionevole per ricevere candidature di qualità',
        'max_applications' => 'Imposta un limite ragionevole per gestire le candidature',
        'is_remote' => 'Seleziona se il lavoro può essere svolto da remoto',
        'is_urgent' => 'Marca come urgente per attirare più attenzione',
        'is_featured' => 'Metti in evidenza per dare più visibilità',
        'event' => 'Associa l\'ingaggio a un evento specifico',
        'group' => 'Associa l\'ingaggio a un gruppo specifico',
        'allow_group_admin_edit' => 'Se attivato, gli admin del gruppo potranno modificare questo ingaggio',
    ],

    // Lingue
    'languages' => [
        'italian' => 'Italiano',
        'english' => 'Inglese',
        'french' => 'Francese',
        'german' => 'Tedesco',
        'spanish' => 'Spagnolo',
        'portuguese' => 'Portoghese',
        'russian' => 'Russo',
        'chinese' => 'Cinese',
        'japanese' => 'Giapponese',
        'arabic' => 'Arabo',
        'other' => 'Altro',
    ],

    // Breadcrumb e navigazione
    'breadcrumb' => [
        'gigs' => 'Ingaggi',
        'my_gigs' => 'I Miei Ingaggi',
        'create_gig' => 'Crea Ingaggio',
        'edit_gig' => 'Modifica Ingaggio',
        'manage_gigs' => 'Gestisci Ingaggi',
        'applications' => 'Candidature',
    ],

    // Creazione
    'create' => [
        'publication_options' => 'Opzioni di Pubblicazione',
    ],

    // Informazioni autore
    'about_author' => 'Informazioni Autore',

    // Sezione Organizzatore
    'organizer_section' => [
        'title' => 'I Miei Eventi',
        'gigs' => 'Ingaggi',
        'add_gig' => 'Aggiungi Ingaggio',
        'no_events' => 'Non hai ancora eventi',
        'create_event_first' => 'Crea prima un evento per aggiungere ingaggi',
    ],

    // Azioni
    'actions' => [
        'read' => 'Leggi',
        'share' => 'Condividi',
        'close_gig' => 'Chiudi Ingaggio',
        'reopen_gig' => 'Riapri Ingaggio',
        'send_global_message' => 'Invia Messaggio Globale',
        'view_gig' => 'Vedi Ingaggio',
        'message' => 'Messaggio',
        'message_placeholder' => 'Scrivi il tuo messaggio per tutti gli utenti...',
        'send_message' => 'Invia Messaggio',
        'confirm_close' => 'Conferma Chiusura',
        'confirm_close_text' => 'Sei sicuro di voler chiudere questo ingaggio? Non sarà più possibile ricevere nuove candidature.',
        'confirm_reopen' => 'Conferma Riapertura',
        'confirm_reopen_text' => 'Sei sicuro di voler riaprire questo ingaggio? Potrà ricevere nuove candidature.',
    ],

    // Candidature
    'applications' => [
        'title' => 'Candidature',
        'my_applications' => 'Le Mie Candidature',
        'pending_applications' => 'Candidature in Attesa',
        'accepted_applications' => 'Candidature Accettate',
        'rejected_applications' => 'Candidature Rifiutate',
        'manage_applications' => 'Gestisci Candidature',
        'applications_list' => 'Lista Candidature',
        'total_applications' => 'Candidature Totali',
        'max_positions' => 'Posizioni Massime',
        'pending' => 'In Attesa',
        'accepted' => 'Accettata',
        'rejected' => 'Rifiutata',
        'withdrawn' => 'Ritirata',
        'experience' => 'Esperienza',
        'portfolio' => 'Portfolio',
        'view_portfolio' => 'Vedi Portfolio',
        'compensation_expectation' => 'Aspettative Compenso',
        'accept' => 'Accetta',
        'reject' => 'Rifiuta',
        'withdraw' => 'Ritira Candidatura',
        'confirm_accept' => 'Conferma Accettazione',
        'confirm_accept_text' => 'Sei sicuro di voler accettare questa candidatura?',
        'confirm_reject' => 'Conferma Rifiuto',
        'confirm_reject_text' => 'Sei sicuro di voler rifiutare questa candidatura?',
        'withdraw_confirm' => 'Sei sicuro di voler ritirare la tua candidatura?',
        'no_applications' => 'Nessuna Candidatura',
        'no_applications_description' => 'Non ci sono ancora candidature per questo ingaggio.',
        'apply' => 'Candidati',
        'message' => 'Messaggio di Candidatura',
        'message_placeholder' => 'Presentati e spiega perché sei interessato a questa posizione...',
        'experience_placeholder' => 'Descrivi la tua esperienza rilevante...',
        'portfolio_placeholder' => 'Link al tuo portfolio o sito web...',
        'availability_placeholder' => 'Descrivi la tua disponibilità...',
        'compensation_expectation_placeholder' => 'Le tue aspettative di compenso...',
        'submit_application' => 'Invia Candidatura',
        'application_sent' => 'Candidatura inviata con successo!',
        'application_withdrawn' => 'Candidatura ritirata con successo!',
        'already_applied' => 'Ti sei già candidato per questo ingaggio',
    ],

    // Messaggi
    'messages' => [
        'application_accepted' => 'Candidatura accettata con successo!',
        'application_rejected' => 'Candidatura rifiutata con successo!',
        'gig_closed' => 'Ingaggio chiuso con successo!',
        'gig_reopened' => 'Ingaggio riaperto con successo!',
        'gig_shared' => 'Ingaggio condiviso con :count utenti!',
        'global_message_sent' => 'Messaggio globale inviato a :count utenti!',
        'audience_not_allowed' => 'Gli utenti audience non possono accedere agli ingaggi',
    ],

    // Notifiche
    'notifications' => [
        'shared_title' => 'Nuovo Ingaggio Condiviso',
        'shared_message' => ':title per l\'evento :event - Posizione: :type - Luogo: :location - Scadenza: :deadline',
    ],
];
