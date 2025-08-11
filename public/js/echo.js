// Configurazione Echo per Reverb
// Questo file viene importato da bootstrap.js

// Configurazione Echo per Reverb
// Questo file viene importato da bootstrap.js

// Funzione per inizializzare Echo
function initEcho() {
    

    // Verifica che Echo sia disponibile
    if (window.Echo) {
        

                // Test di connessione - verifica che socket esista
        if (window.Echo.connector && window.Echo.connector.socket) {
            

            window.Echo.connector.socket.on('connect', () => {
                

                // Emetti evento quando Echo è pronto
                document.dispatchEvent(new CustomEvent('echoReady'));
                
            });

            window.Echo.connector.socket.on('disconnect', () => {
                
            });

            window.Echo.connector.socket.on('error', (error) => {
                console.error('[echo] Errore di connessione:', error);
            });
        } else {
            
            // Se socket non è disponibile, emetti evento comunque
            document.dispatchEvent(new CustomEvent('echoReady'));
            ');
        }

        return true;
    } else {
        console.error('[echo] Echo non è disponibile!');
        return false;
    }
}

// Aspetta che Echo sia disponibile prima di inizializzare
function waitForEcho() {
    if (window.Echo) {
        
        initEcho();
    } else {
        
        setTimeout(waitForEcho, 100);
    }
}

// Inizia ad aspettare Echo
waitForEcho();

// Esporta la funzione per uso esterno
window.initEcho = initEcho;
