<?php

return [
    'scoring' => [
        // Navigation & General
        'dashboard' => 'Dashboard',
        'participants' => 'Partecipanti',
        'scores' => 'Punteggi',
        'rankings' => 'Classifica',
        'return_to_event' => 'Torna all\'evento',
        
        // Actions
        'actions' => 'Azioni',
        'add' => 'Aggiungi',
        'create' => 'Crea',
        'edit' => 'Modifica',
        'delete' => 'Elimina',
        'save' => 'Salva',
        'cancel' => 'Annulla',
        'remove' => 'Rimuovi',
        'update' => 'Aggiorna',
        'close_event' => 'Chiudi evento',
        'finalize_event' => 'Finalizza evento',
        'terminate_event' => 'Termina evento',
        
        // Quick Actions
        'quick_actions' => 'Azioni Rapide',
        'manage_participants' => 'Gestisci Partecipanti',
        'insert_scores' => 'Inserisci Punteggi',
        'view_rankings' => 'Visualizza Classifica',
        'go_to_scores' => 'Vai ai Punteggi',
        'go_to_rankings' => 'Vai alla Classifica',
        'publish_results' => 'Pubblica Risultati',
        'assign_badges_to_winners' => 'Assegna Badge ai Vincitori',
        'calculate_final_rankings' => 'Calcola Classifica Finale',
        'calculate_partial_rankings' => 'Calcola Classifica Parziale',
        'update_rankings_without_closing_event' => 'Aggiorna Classifica senza Chiudere Evento',
        
        // Participants
        'participant' => 'Partecipante',
        'participant_management' => 'Gestione Partecipanti',
        'participant_name' => 'Nome Partecipante',
        'participant_type' => 'Tipo Partecipante',
        'add_participant' => 'Aggiungi Partecipante',
        'add_participants' => 'Aggiungi Partecipanti',
        'add_first_participant_to_event' => 'Aggiungi il primo partecipante all\'evento',
        'no_participants' => 'Nessun partecipante',
        'registered' => 'Registrati',
        'performed_participants' => 'Partecipanti Esibiti',
        'registered_user' => 'Utente Registrato',
        'guest' => 'Ospite',
        'users_added_automatically' => 'Gli utenti vengono aggiunti automaticamente quando accettano inviti o richieste per questo evento Poetry Slam.',
        
        // Participant Status
        'status' => 'Stato',
        'confirmed' => 'Confermato',
        'performed' => 'Esibito',
        'disqualified' => 'Squalificato',
        'no_show' => 'Assente',
        
        // Participant Fields
        'name' => 'Nome',
        'email' => 'Email',
        'phone' => 'Telefono',
        'bio' => 'Biografia',
        'name_nickname_email' => 'Nome / Nickname / Email',
        'search_user' => 'Cerca Utente',
        'performance_order' => 'Ordine di Esibizione',
        'notes' => 'Note',
        'note' => 'Nota',
        'leave_empty_for_auto_assignment' => 'Lascia vuoto per assegnazione automatica',
        
        // Rounds
        'round' => 'Turno',
        'rounds' => 'Turni',
        'round_name' => 'Nome Turno',
        'round_number' => 'Numero Turno',
        'round_scores' => 'Punteggi Turno',
        'add_round' => 'Aggiungi Turno',
        'edit_round' => 'Modifica Turno',
        'no_rounds_configured' => 'Nessun turno configurato',
        'first_round' => 'Primo Turno',
        'semi_final' => 'Semifinale',
        'final' => 'Finale',
        
        // Scoring Types
        'scoring_type' => 'Tipo di Punteggio',
        'average' => 'Media',
        'sum' => 'Somma',
        'best_of' => 'Migliore',
        'scale_0_0_10_0_with_one_decimal' => 'Scala 0.0 - 10.0 con un decimale',
        
        // Scores
        'score' => 'Punteggio',
        'score_entry' => 'Inserimento Punteggi',
        'scores_inserted' => 'Punteggi Inseriti',
        'with_scores' => 'con Punteggi',
        'total_score' => 'Punteggio Totale',
        'scores_are_saved_automatically' => 'I punteggi vengono salvati automaticamente',
        'insert_scores_before_generating_rankings' => 'Inserisci i punteggi prima di generare la classifica',
        'add_participants_before_inserting_scores' => 'Aggiungi partecipanti prima di inserire i punteggi',
        
        // Rankings
        'final_rankings' => 'Classifica Finale',
        'final_rankings_published' => 'Classifica Finale Pubblicata',
        'rankings_ready' => 'Classifica Pronta',
        'rankings_not_calculated' => 'Classifica non Calcolata',
        'rankings_generated' => 'La classifica è stata generata. Non è possibile modificare i partecipanti.',
        'the_rankings_have_been_generated' => 'La classifica è stata generata',
        
        // Points & Badges
        'points' => 'Punti',
        'badge' => 'Badge',
        'assigned_badges' => 'Badge Assegnati',
        'badges_awarded' => 'Badge Assegnati',
        'to_assign' => 'Da Assegnare',
        'assigned' => 'Assegnato',
        'winners' => 'Vincitori',
        
        // Messages & Confirmations
        'success' => 'Successo',
        'warning' => 'Attenzione',
        'error' => 'Errore',
        'event_completed' => 'Evento Completato',
        'data_to_define' => 'Dati da Definire',
        'example' => 'Esempio',
        'recommended' => 'Consigliato',
        'type' => 'Tipo',
        
        // Confirmations
        'are_you_sure_you_want_to_remove_this_participant' => 'Sei sicuro di voler rimuovere questo partecipante?',
        'confirm_delete_round' => 'Sei sicuro di voler eliminare questo turno?',
        'confirm_finalize_event' => 'Sei sicuro di voler finalizzare questo evento?',
        'this_action_will_complete_the_event' => 'Questa azione completerà l\'evento e non potrai più modificare punteggi o partecipanti.',
        'you_will_not_be_able_to_modify_scores' => 'Non potrai più modificare i punteggi dopo aver finalizzato l\'evento',
        'you_have_participants_with_scores' => 'Hai partecipanti con punteggi',
        'yes_finalize' => 'Sì, Finalizza',
    ],
];

