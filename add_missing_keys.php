<?php

echo "🔧 AGGIUNTA CHIAVI MANCANTI PRINCIPALI\n";
echo "======================================\n\n";

// Chiavi mancanti più importanti da aggiungere
$missingKeys = [
    // File: home.php
    'home' => [
        'stats' => 'Statistiche',
        'interactions' => 'Interazioni',
        'loading_video' => 'Caricamento video...',
        'timestamp' => 'Timestamp',
        'cancel' => 'Annulla'
    ],
    
    // File: events_general.php  
    'events_general' => [
        'events' => 'Eventi',
        'dashboard' => 'Dashboard',
        'organizer' => 'Organizzatore',
        'organizers' => 'Organizzatori',
        'free' => 'Gratuito',
        'max_participants' => 'Max partecipanti',
        'create_new_event' => 'Crea nuovo evento',
        'title_placeholder' => 'Inserisci il titolo dell\'evento...',
        'subtitle' => 'Sottotitolo',
        'description' => 'Descrizione',
        'description_placeholder' => 'Descrivi l\'evento...',
        'category' => 'Categoria',
        'select_category' => 'Seleziona categoria',
        'public' => 'Pubblico',
        'private' => 'Privato',
        'public_event_help' => 'Evento visibile a tutti',
        'private_event_help' => 'Evento visibile solo agli invitati',
        'location' => 'Luogo',
        'online' => 'Online',
        'users' => 'Utenti',
        'city' => 'Città',
        'city_placeholder' => 'Inserisci la città...',
        'country' => 'Paese',
        'current_image' => 'Immagine attuale',
        'participants' => 'Partecipanti',
        'max_participants_placeholder' => 'Numero massimo di partecipanti',
        'max_participants_help' => 'Lascia vuoto per numero illimitato',
        'search_users' => 'Cerca utenti',
        'search_users_placeholder' => 'Cerca per nome o email...',
        'search_results' => 'Risultati ricerca',
        'invited_users' => 'Utenti invitati',
        'judge' => 'Giudice',
        'published' => 'Pubblicato',
        'draft' => 'Bozza',
        'role' => 'Ruolo',
        'steps' => 'Passaggi',
        'save_changes' => 'Salva modifiche',
        'create_event' => 'Crea evento',
        'create_event_button' => 'Crea Evento',
        'not_specified' => 'Non specificato',
        'today' => 'Oggi',
        'past_events' => 'Eventi passati',
        'organized_events' => 'Eventi organizzati',
        'pending_invitations' => 'Inviti in sospeso',
        'pending_requests' => 'Richieste in sospeso',
        'confirmed_participants' => 'Partecipanti confermati',
        'participants_status' => 'Stato partecipanti',
        'days_remaining' => 'Giorni rimanenti',
        'confirmed_participants_list' => 'Lista partecipanti confermati',
        'invited_badge' => 'Invitato',
        'request_badge' => 'Richiesta',
        'create_first_event' => 'Crea il tuo primo evento',
        'close' => 'Chiudi',
        'warning' => 'Attenzione',
        'delete_warning_participants' => 'Eliminando questo evento verranno rimossi tutti i partecipanti',
        'not_available' => 'Non disponibile',
        'organizer_label' => 'Organizzatore:',
        'participants_label' => 'Partecipanti:',
        'manage_event' => 'Gestisci evento',
        'upcoming_events' => 'Prossimi eventi',
        'event_public_badge' => 'Pubblico',
        'event_private_badge' => 'Privato',
        'participant_invited' => 'Invitato',
        'participant_applied' => 'Ha fatto richiesta',
        'pending_participants' => 'Partecipanti in attesa',
        'no_participants' => 'Nessun partecipante',
        'first_participant' => 'Diventa il primo partecipante',
        'participant_stats' => 'Statistiche partecipanti',
        'view' => 'Visualizza',
        'manage_event_action' => 'Gestisci evento',
        'expired' => 'Scaduto',
        'participant_apply_title' => 'Candidati per questo evento',
        'participant_apply_role' => 'Ruolo richiesto',
        'participant_apply_role_help' => 'Seleziona il ruolo per cui ti candidi',
        'participant_apply_message' => 'Messaggio',
        'participant_apply_message_help' => 'Spiega perché vuoi partecipare',
        'participant_apply_message_suggestion' => 'Es: Ho esperienza in...',
        'participant_apply_experience' => 'Esperienza',
        'participant_apply_experience_help' => 'Descrivi la tua esperienza',
        'participant_apply_experience_optional' => '(opzionale)',
        'participant_links' => 'Link utili',
        'participant_links_placeholder' => 'Sito web, portfolio, social...',
        'participant_links_help' => 'Link che mostrano il tuo lavoro',
        'participant_terms_accept' => 'Accetto i termini di partecipazione',
        'participant_apply_cancel' => 'Annulla',
        'participant_apply_send' => 'Invia candidatura',
        'participant_apply_validation_role' => 'Seleziona un ruolo',
        'participant_apply_validation_message' => 'Inserisci un messaggio',
        'participant_apply_validation_terms' => 'Devi accettare i termini',
        'participant_apply_sending' => 'Invio in corso...',
        'requests_today' => 'Richieste oggi'
    ],
    
    // File: dashboard.php
    'dashboard' => [
        'dashboard' => 'Dashboard',
        'create_event_button' => 'Crea Evento',
        'statistics' => 'Statistiche',
        'past_events' => 'Eventi Passati',
        'organized_events' => 'Eventi Organizzati',
        'pending_invitations' => 'Inviti in Sospeso',
        'group_invitations' => 'Inviti Gruppi',
        'groups' => 'Gruppi',
        'quick_actions' => 'Azioni Rapide',
        'my_wishlist' => 'La Mia Lista Desideri',
        'view_all' => 'Vedi Tutto',
        'recent_activity' => 'Attività Recente',
        'view_all_activity' => 'Vedi Tutta l\'Attività',
        'no_recent_activity' => 'Nessuna attività recente',
        'user_not_found' => 'Utente non trovato',
        'accept' => 'Accetta',
        'decline' => 'Rifiuta',
        'error' => 'Errore',
        'ok' => 'OK',
        'success' => 'Successo'
    ],
    
    // File: search.php
    'search' => [
        'search_placeholder' => 'Cerca poesie, eventi, utenti...',
        'search_results' => 'Risultati ricerca',
        'all' => 'Tutti',
        'poems' => 'Poesie',
        'events_general' => 'Eventi',
        'videos' => 'Video',
        'gigs' => 'Ingaggi',
        'users' => 'Utenti',
        'search' => 'Cerca',
        'view_all' => 'Vedi tutti',
        'no_results_found' => 'Nessun risultato trovato'
    ],
    
    // File: auth.php
    'auth' => [
        'email' => 'Email',
        'confirm_password' => 'Conferma Password',
        'email_placeholder' => 'Inserisci la tua email',
        'login' => 'Accedi',
        'register' => 'Registrati'
    ],
    
    // File: register.php
    'register' => [
        'events_general' => 'Eventi',
        'venues' => 'Venue',
        'platform_description' => 'Descrizione piattaforma',
        'register' => 'Registrati',
        'full_name' => 'Nome Completo',
        'nickname' => 'Nickname',
        'nickname_placeholder' => 'Il tuo nickname pubblico',
        'optional' => 'Opzionale',
        'email' => 'Email',
        'email_placeholder' => 'La tua email',
        'password' => 'Password',
        'four_main_roles' => 'Quattro ruoli principali',
        'already_have_account' => 'Hai già un account?',
        'login' => 'Accedi',
        'why_join_slam_in' => 'Perché unirsi a Slam In?',
        'fast_registration' => 'Registrazione veloce',
        'flexible_roles' => 'Ruoli flessibili',
        'complete_ecosystem' => 'Ecosistema completo'
    ],
    
    // File: notifications.php
    'notifications' => [
        'notifications' => 'Notifiche',
        'mark_all_read' => 'Segna tutto come letto',
        'no_notifications' => 'Nessuna notifica',
        'notifications_placeholder' => 'Non hai notifiche',
        'new' => 'Nuovo',
        'last_7_days' => 'Ultimi 7 giorni',
        'manage_notifications' => 'Gestisci notifiche',
        'read' => 'Letto',
        'unread' => 'Non letto'
    ]
];

$totalAdded = 0;

foreach ($missingKeys as $file => $keys) {
    $filePath = "lang/it/$file.php";
    
    echo "📄 Aggiornando: $file.php\n";
    
    if (!file_exists($filePath)) {
        echo "   ❌ File $filePath non trovato\n";
        continue;
    }
    
    $content = file_get_contents($filePath);
    
    // Rimuove la parentesi graffa di chiusura
    $content = rtrim($content);
    $content = rtrim($content, '];');
    
    $added = 0;
    foreach ($keys as $key => $value) {
        // Controlla se la chiave esiste già
        if (!preg_match("/[\'\"]({$key})[\'"]\s*=>/", $content)) {
            $content .= "\n    '$key' => '$value',";
            $added++;
            echo "   ✅ Aggiunta: $key\n";
        } else {
            echo "   ℹ️  Esistente: $key\n";
        }
    }
    
    if ($added > 0) {
        $content .= "\n\n];";
        file_put_contents($filePath, $content);
        echo "   📝 Salvato con $added nuove chiavi\n";
        $totalAdded += $added;
    } else {
        echo "   ℹ️  Nessuna nuova chiave aggiunta\n";
    }
    
    echo "\n";
}

echo "📊 RIEPILOGO:\n";
echo "=============\n";
echo "Chiavi aggiunte: $totalAdded\n";
echo "File aggiornati: " . count($missingKeys) . "\n\n";

echo "🎯 PROSSIMI PASSI:\n";
echo "==================\n";
echo "1. Testare le pagine principali\n";
echo "2. Aggiungere altre chiavi mancanti se necessario\n";
echo "3. Verificare che non ci siano più testi [en]\n";

