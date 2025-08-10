// Configurazione Echo per Reverb
// Questo file viene importato da bootstrap.js

// Verifica che Echo sia disponibile
if (window.Echo) {
    console.log('[echo] Echo configurato correttamente:', window.Echo);

    // Test di connessione
    window.Echo.connector.socket.on('connect', () => {
        console.log('[echo] Connesso a Reverb');
    });

    window.Echo.connector.socket.on('disconnect', () => {
        console.log('[echo] Disconnesso da Reverb');
    });

    window.Echo.connector.socket.on('error', (error) => {
        console.error('[echo] Errore di connessione:', error);
    });
} else {
    console.error('[echo] Echo non è disponibile!');
}
