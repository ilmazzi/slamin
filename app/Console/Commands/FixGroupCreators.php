<?php

namespace App\Console\Commands;

use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Console\Command;

class FixGroupCreators extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'groups:fix-creators';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggiunge i creatori mancanti come admin dei loro gruppi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Inizio correzione creatori gruppi...');

        $groups = Group::with('creator')->get();
        $fixedCount = 0;

        foreach ($groups as $group) {
            // Verifica se il creatore è già membro del gruppo
            $existingMember = GroupMember::where('group_id', $group->id)
                                        ->where('user_id', $group->created_by)
                                        ->first();

            if (!$existingMember && $group->creator) {
                // Aggiungi il creatore come admin
                GroupMember::create([
                    'group_id' => $group->id,
                    'user_id' => $group->created_by,
                    'role' => 'admin',
                    'joined_at' => $group->created_at,
                ]);

                $this->line("✓ Aggiunto creatore come admin per gruppo: {$group->name}");
                $fixedCount++;
            } elseif ($existingMember && $existingMember->role !== 'admin') {
                // Aggiorna il ruolo a admin se non lo è già
                $existingMember->update(['role' => 'admin']);
                $this->line("✓ Aggiornato ruolo a admin per gruppo: {$group->name}");
                $fixedCount++;
            }
        }

        $this->info("Correzione completata! {$fixedCount} gruppi corretti.");
        
        return 0;
    }
} 