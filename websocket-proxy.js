import { WebSocketServer } from 'ws';

// Configurazione
const REVERB_PORT = 8080; // Porta di Reverb
const PROXY_PORT = 8443; // Porta per il proxy

// WebSocket server che accetta WS
const wss = new WebSocketServer({ port: PROXY_PORT });

console.log(`Proxy WebSocket avviato su ws://localhost:${PROXY_PORT}`);

wss.on('connection', (ws, req) => {
    console.log('Nuova connessione WS ricevuta');

    // Connessione a Reverb
    const reverbWs = new WebSocket(`ws://localhost:${REVERB_PORT}${req.url}`);

    reverbWs.on('open', () => {
        console.log('Connesso a Reverb');
    });

    reverbWs.on('message', (data) => {
        ws.send(data);
    });

    reverbWs.on('close', () => {
        console.log('Connessione a Reverb chiusa');
        ws.close();
    });

    reverbWs.on('error', (error) => {
        console.error('Errore connessione a Reverb:', error);
        ws.close();
    });

    ws.on('message', (data) => {
        reverbWs.send(data);
    });

    ws.on('close', () => {
        console.log('Connessione WS chiusa');
        reverbWs.close();
    });

    ws.on('error', (error) => {
        console.error('Errore WS:', error);
        reverbWs.close();
    });
});
