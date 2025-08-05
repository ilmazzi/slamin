<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\WebSocket\SimpleWebSocketServer;

class StartWebSocketServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websocket:start {--host=0.0.0.0} {--port=8080}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Avvia il WebSocket server per la chat in tempo reale';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $host = $this->option('host');
        $port = $this->option('port');

        $this->info("Avvio WebSocket server su {$host}:{$port}...");
        $this->info("Premi Ctrl+C per fermare il server");

        try {
            $server = new SimpleWebSocketServer();
            $server->start($host, $port);
        } catch (\Exception $e) {
            $this->error("Errore nell'avvio del WebSocket server: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
