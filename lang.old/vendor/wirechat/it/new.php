<?php

return [

    /**-------------------------
     * New Chat
     *------------------------*/
    'chat' => [
        'heading' => [
            'label' => 'Nuova Chat',
        ],
        'inputs' => [
            'search' => [
                'label' => 'Cerca',
                'placeholder' => 'Cerca',
            ],
        ],
        'labels' => [
            'no_users_found' => 'Nessun utente trovato',
        ],
        'actions' => [
            'start_chat' => [
                'label' => 'Avvia Chat',
            ],
        ],
    ],

    /**-------------------------
     * New Group
     *------------------------*/
    'group' => [
        'heading' => [
            'label' => 'Nuovo Gruppo',
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
            'search' => [
                'label' => 'Cerca',
                'placeholder' => 'Cerca Membri',
            ],
        ],
        'labels' => [
            'members' => 'Membri',
            'no_members_added' => 'Nessun membro aggiunto ancora',
        ],
        'actions' => [
            'create' => [
                'label' => 'Crea Gruppo',
            ],
        ],
        'messages' => [
            'members_limit_error' => 'I membri non possono superare :count',
            'member_already_exists' => 'Membro già aggiunto',
        ],
    ],

];

