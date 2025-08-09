// Echo disabilitato (Reverb rimosso). Definiamo un init opzionale per evitare errori runtime.
window.initEcho = function initEcho() {
    console.warn('[echo] initEcho chiamato ma Reverb è disabilitato');
    return undefined;
};
