<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ScanMorphTypes extends Command
{
    protected $signature = 'morph:scan {--like=* : Filtra per pattern (es. video, App/Models)}';
    protected $description = 'Scansiona il database per colonne *_type e mostra i valori distinti con i conteggi.';

    public function handle(): int
    {
        $this->info('> Scansione colonne *_type in tutte le tabelle...');

        $connection = DB::connection();
        $driver = $connection->getDriverName();

        if (!in_array($driver, ['mysql', 'mariadb', 'pgsql', 'sqlite'])) {
            $this->error("Driver non supportato: {$driver}");
            return self::FAILURE;
        }

        // Recupera elenco tabelle e colonne
        $columns = $this->getTypeColumns($driver, $connection->getDatabaseName());

        if (empty($columns)) {
            $this->warn('Nessuna colonna che termina con _type trovata.');
            return self::SUCCESS;
        }

        $filters = (array)$this->option('like');
        $hasFilter = !empty($filters);

        $foundAny = false;

        foreach ($columns as $item) {
            $table = $item['table'];
            $column = $item['column'];

            try {
                $rows = DB::table($table)
                    ->select($column . ' as type', DB::raw('COUNT(*) as cnt'))
                    ->whereNotNull($column)
                    ->groupBy($column)
                    ->orderBy('cnt', 'desc')
                    ->get();

                if ($rows->isEmpty()) {
                    continue;
                }

                // Applica filtro opzionale
                if ($hasFilter) {
                    $filtered = $rows->filter(function ($r) use ($filters) {
                        foreach ($filters as $f) {
                            if (Str::contains((string) $r->type, $f)) {
                                return true;
                            }
                        }
                        return false;
                    });
                    if ($filtered->isEmpty()) {
                        continue;
                    }
                    $rows = $filtered->values();
                }

                $foundAny = true;
                $this->line('');
                $this->info("Tabella: {$table}  |  Colonna: {$column}");
                $this->table(['type', 'count'], $rows->map(fn ($r) => [(string)$r->type, (int)$r->cnt])->toArray());
            } catch (\Throwable $e) {
                $this->error("Errore leggendo {$table}.{$column}: {$e->getMessage()}");
            }
        }

        if (!$foundAny) {
            $this->warn('Nessun valore trovato (o filtri troppo restrittivi).');
        }

        return self::SUCCESS;
    }

    /**
     * Restituisce le colonne che terminano con "_type".
     */
    protected function getTypeColumns(string $driver, ?string $database): array
    {
        $results = [];

        switch ($driver) {
            case 'mysql':
            case 'mariadb':
                $rows = DB::select("
                    SELECT TABLE_NAME as tbl, COLUMN_NAME as col
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_SCHEMA = ?
                      AND COLUMN_NAME LIKE '%\\_type' ESCAPE '\\'
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
                      AND column_name LIKE '%_type'
                    ORDER BY table_name, column_name
                ", [$database]);
                foreach ($rows as $r) {
                    $results[] = ['table' => $r->tbl, 'column' => $r->col];
                }
                break;

            case 'sqlite':
                // Per sqlite non c'è information_schema: facciamo best effort interrogando PRAGMA
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
