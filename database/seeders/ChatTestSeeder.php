<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Participant;

class ChatTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prendi i primi 3 utenti dal database
        $users = User::take(3)->get();
        
        if ($users->count() < 2) {
            $this->command->info('Non ci sono abbastanza utenti per creare conversazioni di test');
            return;
        }

        // Crea una conversazione privata tra i primi due utenti
        $privateConversation = Conversation::create([
            'type' => 'private',
            'name' => null,
        ]);

        // Aggiungi i partecipanti
        Participant::create([
            'conversation_id' => $privateConversation->id,
            'user_id' => $users[0]->id,
            'role' => 'member',
        ]);

        Participant::create([
            'conversation_id' => $privateConversation->id,
            'user_id' => $users[1]->id,
            'role' => 'member',
        ]);

        // Crea alcuni messaggi di prova
        Message::create([
            'conversation_id' => $privateConversation->id,
            'user_id' => $users[0]->id,
            'body' => 'Ciao! Come stai?',
            'type' => 'text',
        ]);

        Message::create([
            'conversation_id' => $privateConversation->id,
            'user_id' => $users[1]->id,
            'body' => 'Ciao! Tutto bene, grazie! E tu?',
            'type' => 'text',
        ]);

        Message::create([
            'conversation_id' => $privateConversation->id,
            'user_id' => $users[0]->id,
            'body' => 'Anche io tutto bene! 😊',
            'type' => 'text',
        ]);

        // Se ci sono almeno 3 utenti, crea anche una conversazione di gruppo
        if ($users->count() >= 3) {
            $groupConversation = Conversation::create([
                'type' => 'group',
                'name' => 'Gruppo di Test',
                'description' => 'Una conversazione di gruppo per testare la chat',
            ]);

            // Aggiungi tutti e 3 gli utenti al gruppo
            foreach ($users as $user) {
                Participant::create([
                    'conversation_id' => $groupConversation->id,
                    'user_id' => $user->id,
                    'role' => $user->id === $users[0]->id ? 'admin' : 'member',
                ]);
            }

            // Crea alcuni messaggi nel gruppo
            Message::create([
                'conversation_id' => $groupConversation->id,
                'user_id' => $users[0]->id,
                'body' => 'Benvenuti nel gruppo di test!',
                'type' => 'text',
            ]);

            Message::create([
                'conversation_id' => $groupConversation->id,
                'user_id' => $users[1]->id,
                'body' => 'Grazie per l\'invito! 👍',
                'type' => 'text',
            ]);

            Message::create([
                'conversation_id' => $groupConversation->id,
                'user_id' => $users[2]->id,
                'body' => 'Perfetto, funziona tutto!',
                'type' => 'text',
            ]);
        }

        $this->command->info('Conversazioni di test create con successo!');
    }
}