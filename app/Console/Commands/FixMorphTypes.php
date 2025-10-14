<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FixMorphTypes extends Command
{
    protected $signature = 'morph:fix 
                            {--from= : Valore corrente da correggere (es. "App/Models/video")} 
                            {--to= : Alias/chiave di morph map di destinazione (es. "video")}
                            {--dry : Mostra cosa farebbe senza eseguire}';
    protected $description = 'Sostituisce in tutte le colonne *_type un valore legacy con un alias desiderato.';

    public function handle(): int
    {
        $from = (string) $this->option('from');
        $to   = (string) $this->option('to');
        $dry  = (bool) $this->option('dry');

        if ($from === '' || $to === '') {
            $this->error('Devi specificare --from e --to.');
            return self::FAILURE;
        }

        $this->info("> Normalizzazione morph type: '{$from}'  =>  '{$to}'");
        if ($dry) {
            $this->warn('** DRY RUN ** Nessun update verrà eseguito.');
        }

        $columns = $this->getTypeColumns();

        $totalUpdated = 0;

        foreach ($columns as $item) {
            $table  = $item['table'];
            $column = $item['column'];

            try {
                $count = DB::table($table)->where($column, $from)->count();

                if ($count > 0) {
                    $this->line(" - {$table}.{$column}  |  match: {$count}");
                    if (!$dry) {
                        $updated = DB::table($table)->where($column, $from)->update([$column => $to]);
                        $totalUpdated += $updated;
                    }
                }
            } catch (\Throwable $e) {
                $this->error("Errore su {$table}.{$column}: {$e->getMessage()}");
            }
        }

        if ($dry) {
            $this->info('DRY RUN completato. Nessuna riga modificata.');
        } else {
            $this->info("Aggiornamento completato. Righe totali modificate: {$totalUpdated}");
        }

        return self::SUCCESS;
    }

    protected function getTypeColumns(): array
    {
        $driver = DB::connection()->getDriverName();
        $database = DB::connection()->getDatabaseName();
        $results = [];

        switch ($driver) {
            case 'mysql':
            case 'mariadb':
                $rows = DB::select("
                    SELECT TABLE_NAME as tbl, COLUMN_NAME as col
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_SCHEMA = ?
                      AND RIGHT(COLUMN_NAME, 5) = '_type'
                    ORDER BY TABLE_NAME, COLUMN_NAME
                ", [$database]);
                foreach ($rows as $r) {
                    $results[] = ['table' => $r->tbl, 'column' => $r->col];
                }
                break;

            case 'pgsql':
                $rows = DB::select("
                    SELECT table_name as tbl, column_name as col
                    FROM information_schema.columns
                    WHERE table_catalog = ?
                      AND RIGHT(column_name, 5) = '_type'
                    ORDER BY table_name, column_name
                ", [$database]);
                foreach ($rows as $r) {
                    $results[] = ['table' => $r->tbl, 'column' => $r->col];
                }
                break;

            case 'sqlite':
                $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
                foreach ($tables as $t) {
                    $cols = DB::select("PRAGMA table_info({$t->name})");
                    foreach ($cols as $c) {
                        if (Str::endsWith($c->name, '_type')) {
                            $results[] = ['table' => $t->name, 'column' => $c->name];
                        }
                    }
                }
                break;
        }

        return $results;
    }
}
