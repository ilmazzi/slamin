<?php

return [

    /**-------------------------
     * Chat
     *------------------------*/
    'labels' => [

        'you_replied_to_yourself' => 'Hai risposto a te stesso',
        'participant_replied_to_you' => ':sender ha risposto a te',
        'participant_replied_to_themself' => ':sender ha risposto a se stesso',
        'participant_replied_other_participant' => ':sender ha risposto a :receiver',
        'you' => 'Tu',
        'user' => 'Utente',
        'replying_to' => 'Rispondendo a :participant',
        'replying_to_yourself' => 'Rispondendo a te stesso',
        'attachment' => 'Allegato',
    ],

    'inputs' => [
        'message' => [
            'label' => 'Messaggio',
            'placeholder' => 'Scrivi un messaggio',
        ],
        'media' => [
            'label' => 'Media',
            'placeholder' => 'Media',
        ],
        'files' => [
            'label' => 'File',
            'placeholder' => 'File',
        ],
    ],

    'message_groups' => [
        'today' => 'Oggi',
        'yesterday' => 'Ieri',

    ],

    'actions' => [
        'open_group_info' => [
            'label' => 'Info Gruppo',
        ],
        'open_chat_info' => [
            'label' => 'Info Chat',
        ],
        'close_chat' => [
            'label' => 'Chiudi Chat',
        ],
        'clear_chat' => [
            'label' => 'Cancella Cronologia Chat',
            'confirmation_message' => 'Sei sicuro di voler cancellare la cronologia della chat? Questo cancellerà la chat solo dal tuo lato e non influenzerà gli altri partecipanti.',
        ],
        'delete_chat' => [
            'label' => 'Elimina Chat',
            'confirmation_message' => 'Sei sicuro di voler eliminare questa chat? Questo rimuoverà la chat solo dal tuo lato e non la eliminerà per gli altri partecipanti.',
        ],

        'delete_for_everyone' => [
            'label' => 'Elimina per tutti',
            'confirmation_message' => 'Sei sicuro?',
        ],
        'delete_for_me' => [
            'label' => 'Elimina per me',
            'confirmation_message' => 'Sei sicuro?',
        ],
        'reply' => [
            'label' => 'Rispondi',
        ],

        'exit_group' => [
            'label' => 'Esci dal Gruppo',
            'confirmation_message' => 'Sei sicuro di voler uscire da questo gruppo?',
        ],
        'upload_file' => [
            'label' => 'File',
        ],
        'upload_media' => [
            'label' => 'Foto & Video',
        ],
    ],

    'messages' => [

        'cannot_exit_self_or_private_conversation' => 'Non puoi uscire da una conversazione con te stesso o privata',
        'owner_cannot_exit_conversation' => 'Il proprietario non può uscire dalla conversazione',
        'rate_limit' => 'Troppi tentativi! Rallenta',
        'conversation_not_found' => 'Conversazione non trovata.',
        'conversation_id_required' => 'È richiesto un ID conversazione',
        'invalid_conversation_input' => 'Input conversazione non valido.',
    ],

    /**-------------------------
     * Info Component
     *------------------------*/

    'info' => [
        'heading' => [
            'label' => 'Info Chat',
        ],
        'actions' => [
            'delete_chat' => [
                'label' => 'Elimina Chat',
                'confirmation_message' => 'Sei sicuro di voler eliminare questa chat? Questo rimuoverà la chat solo dal tuo lato e non la eliminerà per gli altri partecipanti.',
            ],
        ],
        'messages' => [
            'invalid_conversation_type_error' => 'Sono consentite solo conversazioni private e con te stesso',
        ],

    ],

    /**-------------------------
     * Group Folder
     *------------------------*/

    'group' => [

        // Group info component
        'info' => [
            'heading' => [
                'label' => 'Info Gruppo',
            ],
            'labels' => [
                'members' => 'Membri',
                'add_description' => 'Aggiungi una descrizione al gruppo',
            ],
            'inputs' => [
                'name' => [
                    'label' => 'Nome Gruppo',
                    'placeholder' => 'Inserisci Nome',
                ],
                'description' => [
                    'label' => 'Descrizione',
                    'placeholder' => 'Opzionale',
                ],
                'photo' => [
                    'label' => 'Foto',
                ],
            ],
            'actions' => [
                'delete_group' => [
                    'label' => 'Elimina Gruppo',
                    'confirmation_message' => 'Sei sicuro di voler eliminare questo Gruppo?',
                    'helper_text' => 'Prima di poter eliminare il gruppo, devi rimuovere tutti i membri del gruppo.',
                ],
                'add_members' => [
                    'label' => 'Aggiungi Membri',
                ],
                'group_permissions' => [
                    'label' => 'Permessi Gruppo',
                ],
                'exit_group' => [
                    'label' => 'Esci dal Gruppo',
                    'confirmation_message' => 'Sei sicuro di voler uscire dal Gruppo?',

                ],
            ],
            'messages' => [
                'invalid_conversation_type_error' => 'Sono consentite solo conversazioni di gruppo',
            ],
        ],
        // Members component
        'members' => [
            'heading' => [
                'label' => 'Membri',
            ],
            'inputs' => [
                'search' => [
                    'label' => 'Cerca',
                    'placeholder' => 'Cerca Membri',
                ],
            ],
            'labels' => [
                'members' => 'Membri',
                'owner' => 'Proprietario',
                'admin' => 'Amministratore',
                'no_members_found' => 'Nessun Membro trovato',
            ],
            'actions' => [
                'send_message_to_yourself' => [
                    'label' => 'Invia messaggio a te stesso',

                ],
                'send_message_to_member' => [
                    'label' => 'Invia messaggio a :member',

                ],
                'dismiss_admin' => [
                    'label' => 'Rimuovi come Amministratore',
                    'confirmation_message' => 'Sei sicuro di voler rimuovere :member come Amministratore?',
                ],
                'make_admin' => [
                    'label' => 'Rendi Amministratore',
                    'confirmation_message' => 'Sei sicuro di voler rendere :member un Amministratore?',
                ],
                'remove_from_group' => [
                    'label' => 'Rimuovi',
                    'confirmation_message' => 'Sei sicuro di voler rimuovere :member da questo Gruppo?',
                ],
                'load_more' => [
                    'label' => 'Carica altro',
                ],

            ],
            'messages' => [
                'invalid_conversation_type_error' => 'Sono consentite solo conversazioni di gruppo',
            ],
        ],
        // add-Members component
        'add_members' => [
            'heading' => [
                'label' => 'Aggiungi Membri',
            ],
            'inputs' => [
                'search' => [
                    'label' => 'Cerca',
                    'placeholder' => 'Cerca',
                ],
            ],
            'labels' => [

            ],
            'actions' => [
                'save' => [
                    'label' => 'Salva',

                ],

            ],
            'messages' => [
                'invalid_conversation_type_error' => 'Sono consentite solo conversazioni di gruppo',
                'members_limit_error' => 'I membri non possono superare :count',
                'member_already_exists' => 'Già aggiunto al gruppo',
            ],
        ],
        // permissions component
        'permissions' => [
            'heading' => [
                'label' => 'Permessi',
            ],
            'inputs' => [
                'search' => [
                    'label' => 'Cerca',
                    'placeholder' => 'Cerca',
                ],
            ],
            'labels' => [
                'members_can' => 'I membri possono',

            ],
            'actions' => [
                'edit_group_information' => [
                    'label' => 'Modifica Informazioni Gruppo',
                    'helper_text' => 'Questo include il nome, l\'icona e la descrizione',
                ],
                'send_messages' => [
                    'label' => 'Invia Messaggi',
                ],
                'add_other_members' => [
                    'label' => 'Aggiungi Altri Membri',
                ],

            ],
            'messages' => [
            ],
        ],

    ],

];
