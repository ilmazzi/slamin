<?php

return [

    // new-chat component
    'chat' => [
        'labels' => [
            'heading' => ' Nuova Chat',
            'you' => 'Tu',

        ],

        'inputs' => [
            'search' => [
                'label' => 'Cerca conversazioni',
                'placeholder' => 'Cerca',
            ],
        ],

        'actions' => [
            'new_group' => [
                'label' => 'Nuovo gruppo',
            ],

        ],

        'messages' => [

            'empty_search_result' => 'Nessun utente trovato che corrisponde alla tua ricerca.',
        ],
    ],

    // new-group component
    'group' => [
        'labels' => [
            'heading' => ' Nuovo Gruppo',
            'add_members' => ' Aggiungi Membri',

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
            'search' => [
                'label' => 'Cerca',
                'placeholder' => 'Cerca',
            ],
            'photo' => [
                'label' => 'Foto',
            ],
        ],

        'actions' => [
            'cancel' => [
                'label' => 'Annulla',
            ],
            'next' => [
                'label' => 'Avanti',
            ],
            'create' => [
                'label' => 'Crea',
            ],

        ],

        'messages' => [
            'members_limit_error' => 'I membri non possono superare :count',
            'empty_search_result' => 'Nessun utente trovato che corrisponde alla tua ricerca.',
        ],
    ],

];
