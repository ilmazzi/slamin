<?php

return [
    // Titoli principali
    'title' => 'Gruppi',
    'my_groups' => 'I Miei Gruppi',
    'all_groups' => 'Tutti i Gruppi',
    'create_group' => 'Crea Gruppo',
    'edit_group' => 'Modifica Gruppo',
    'group_details' => 'Dettagli Gruppo',
    'group_members' => 'Membri del Gruppo',
    'group_invitations' => 'Inviti del Gruppo',
    'group_join_requests' => 'Richieste di Partecipazione',

    // Azioni
    'create' => 'Crea Gruppo',
    'edit' => 'Modifica',
    'delete' => 'Elimina',
    'join' => 'Partecipa',
    'leave' => 'Lascia',
    'invite' => 'Invita',
    'accept' => 'Accetta',
    'decline' => 'Rifiuta',
    'promote' => 'Promuovi',
    'demote' => 'Degrada',
    'remove' => 'Rimuovi',
    'send_request' => 'Invia Richiesta',
    'cancel_request' => 'Annulla Richiesta',

    // Campi del gruppo
    'name' => 'Nome del Gruppo',
    'description' => 'Descrizione',
    'image' => 'Immagine del Gruppo',
    'visibility' => 'Visibilità',
    'visibility_public' => 'Pubblico',
    'visibility_private' => 'Privato',
    'created_by' => 'Creato da',
    'created_at' => 'Creato il',
    'members_count' => 'Numero di Membri',
    'role' => 'Ruolo',
    'joined_at' => 'Partecipante dal',

    // Ruoli
    'role_admin' => 'Amministratore',
    'role_moderator' => 'Moderatore',
    'role_member' => 'Membro',

    // Messaggi
    'group_created' => 'Gruppo creato con successo!',
    'group_updated' => 'Gruppo aggiornato con successo!',
    'group_deleted' => 'Gruppo eliminato con successo!',
    'joined_group' => 'Ti sei unito al gruppo con successo!',
    'left_group' => 'Hai lasciato il gruppo.',
    'member_removed' => 'Membro rimosso dal gruppo.',
    'member_promoted' => 'Membro promosso con successo.',
    'member_demoted' => 'Membro degradato con successo.',
    'invitation_sent' => 'Invito inviato con successo!',
    'invitation_accepted' => 'Invito accettato con successo!',
    'invitation_declined' => 'Invito rifiutato.',
    'request_sent' => 'Richiesta di partecipazione inviata!',
    'request_accepted' => 'Richiesta di partecipazione accettata!',
    'request_declined' => 'Richiesta di partecipazione rifiutata.',

    // Messaggi di errore
    'not_found' => 'Gruppo non trovato.',
    'access_denied' => 'Non hai i permessi per accedere a questo gruppo.',
    'already_member' => 'Sei già membro di questo gruppo.',
    'not_member' => 'Non sei membro di questo gruppo.',
    'invitation_exists' => 'Hai già un invito pendente per questo gruppo.',
    'request_exists' => 'Hai già una richiesta pendente per questo gruppo.',
    'cannot_leave_admin' => 'Non puoi lasciare un gruppo di cui sei amministratore. Promuovi prima un altro membro.',
    'cannot_remove_admin' => 'Non puoi rimuovere un amministratore. Degradalo prima.',
    'cannot_promote_admin' => 'Questo utente è già amministratore.',
    'cannot_demote_member' => 'Questo utente è già un membro normale.',

    // Filtri e ricerca
    'search_placeholder' => 'Cerca gruppi...',
    'filter_all' => 'Tutti i Gruppi',
    'filter_my_groups' => 'I Miei Gruppi',
    'filter_public' => 'Gruppi Pubblici',
    'filter_private' => 'Gruppi Privati',
    'filter_admin' => 'Gruppi che Amministro',

    // Form labels
    'group_name_placeholder' => 'Inserisci il nome del gruppo',
    'group_description_placeholder' => 'Descrivi il tuo gruppo...',
    'invitation_message_placeholder' => 'Messaggio di invito (opzionale)',
    'join_request_message_placeholder' => 'Messaggio di richiesta (opzionale)',
    'invite_members' => 'Invita Membri',
    'search_users_placeholder' => 'Cerca per nome, nickname o email...',
    'search_results' => 'Risultati Ricerca',
    'invited_users' => 'Utenti Invitati',

    // Statistiche
    'stats' => 'Statistiche',
    'total_members' => 'Totale Membri',
    'admins_count' => 'Amministratori',
    'moderators_count' => 'Moderatori',
    'members_count_label' => 'Membri',
    'pending_invitations' => 'Inviti in Attesa',
    'pending_requests' => 'Richieste in Attesa',

    // Eventi del gruppo
    'group_events' => 'Eventi del Gruppo',
    'no_group_events' => 'Nessun evento associato a questo gruppo.',
    'create_group_event' => 'Crea Evento del Gruppo',

    // Permessi eventi
    'event_permissions' => 'Permessi Eventi',
    'creator_only' => 'Solo Creatore',
    'group_admins' => 'Amministratori del Gruppo',
    'group_members' => 'Membri del Gruppo',

    // Paginazione
    'showing' => 'Mostrando',
    'to' => 'a',
    'of' => 'di',
    'results' => 'risultati',

    // Azioni rapide
    'quick_actions' => 'Azioni Rapide',
    'view_members' => 'Visualizza Membri',
    'manage_invitations' => 'Gestisci Inviti',
    'manage_requests' => 'Gestisci Richieste',
    'invite_members' => 'Invita Membri',
    'invite_email' => 'Email Utente',
    'invite_email_placeholder' => 'Inserisci l\'email dell\'utente da invitare',
    'invite_email_help' => 'L\'utente deve essere già registrato sulla piattaforma',
    'invite_message' => 'Messaggio di Invito',
    'invite_message_placeholder' => 'Messaggio opzionale da includere nell\'invito...',
    'invite_message_help' => 'Massimo 500 caratteri',
    'send_invitation' => 'Invia Invito',
    'invitation_info' => 'Informazioni sugli Inviti',
    'invitation_info_1' => 'Gli inviti sono validi per 7 giorni',
    'invitation_info_2' => 'L\'utente riceverà una notifica via email',
    'invitation_info_3' => 'Puoi annullare un invito in qualsiasi momento',
    'group_settings' => 'Impostazioni Gruppo',

    // Conferme
    'confirm_delete' => 'Sei sicuro di voler eliminare questo gruppo?',
    'confirm_leave' => 'Sei sicuro di voler lasciare questo gruppo?',
    'confirm_leave_title' => 'Conferma Uscita',
    'confirm_remove_member' => 'Sei sicuro di voler rimuovere questo membro?',
    'confirm_decline_invitation' => 'Sei sicuro di voler rifiutare questo invito?',
    'confirm_cancel_request' => 'Sei sicuro di voler annullare questa richiesta?',

    // Stati
    'status_pending' => 'In Attesa',
    'status_accepted' => 'Accettato',
    'status_declined' => 'Rifiutato',
    'status_expired' => 'Scaduto',

    // Azioni inviti
    'accept_invitation' => 'Accetta Invito',
    'decline_invitation' => 'Rifiuta Invito',
    'confirm_accept_invitation' => 'Sei sicuro di voler accettare l\'invito al gruppo ":group"?',
    'confirm_decline_invitation' => 'Sei sicuro di voler rifiutare l\'invito al gruppo ":group"?',

    // Statistiche inviti
    'invitations_pending' => 'In Attesa',
    'invitations_accepted' => 'Accettati',
    'invitations_declined' => 'Rifiutati',
    'invitations_expired' => 'Scaduti',

    // Messaggi di validazione
    'select_user_from_search' => 'Seleziona un utente dalla ricerca',

    // Anteprima immagine
    'image_preview' => 'Anteprima Immagine',
    'new_image_preview' => 'Anteprima Nuova Immagine',
    'image_removed' => 'Immagine rimossa',
    'image_removed_message' => 'L\'immagine è stata rimossa dalla selezione',

    // Informazioni aggiuntive
    'group_info' => 'Informazioni Gruppo',
    'member_since' => 'Membro dal',
    'invited_by' => 'Invitato da',
    'processed_by' => 'Processato da',
    'expires_at' => 'Scade il',
    'processed_at' => 'Processato il',

    // Vuoto
    'no_groups' => 'Nessun gruppo trovato.',
    'no_members' => 'Nessun membro in questo gruppo.',
    'no_invitations' => 'Nessun invito in attesa.',
    'no_requests' => 'Nessuna richiesta in attesa.',
    'no_my_groups' => 'Non hai ancora creato nessun gruppo.',
    'no_joined_groups' => 'Non sei ancora membro di nessun gruppo.',

    // Suggerimenti
    'tips' => [
        'create_group' => 'Crea un gruppo per organizzare eventi e collaborare con altri poeti e organizzatori.',
        'invite_members' => 'Invita altri utenti per far crescere la tua community.',
        'manage_permissions' => 'Gestisci i permessi per mantenere il controllo del tuo gruppo.',
        'group_events' => 'Associa gli eventi al gruppo per una migliore organizzazione.',
        'public_visibility' => 'Chiunque può vedere e richiedere di partecipare al gruppo.',
        'private_visibility' => 'Solo gli utenti invitati possono partecipare al gruppo.',
    ],

    // Messaggi aggiuntivi
    'delete_warning' => 'Questa azione eliminerà definitivamente il gruppo e tutti i suoi dati.',
    'delete_confirmation_text' => 'Sei sicuro di voler eliminare questo gruppo? Questa azione non può essere annullata.',
    'delete_confirmation_members' => 'Tutti i membri verranno rimossi dal gruppo',
    'delete_confirmation_events' => 'Gli eventi associati al gruppo verranno disassociati',
    'delete_confirmation_invitations' => 'Tutte le invitazioni e richieste pendenti verranno eliminate',
    'invite_first_member' => 'Invita il primo membro per iniziare a costruire la tua community.',
    'you' => 'Tu',
];
