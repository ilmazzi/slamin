// Gestione logo responsive per sidebar collassata
console.log('Script sidebar-logo.js caricato');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM caricato, cerco elementi...');

    const logoFull = document.querySelector('.logo-full');
    const logoIcon = document.querySelector('.logo-icon');
    const toggleBtn = document.querySelector('.header-toggle');

    console.log('Logo full trovato:', !!logoFull);
    console.log('Logo icon trovato:', !!logoIcon);
    console.log('Toggle trovato:', !!toggleBtn);

    if (!logoFull || !logoIcon || !toggleBtn) {
        console.log('Elementi non trovati');
        return;
    }

    console.log('Tutti gli elementi trovati, imposto event listener');

    // Stato del logo (false = orizzontale, true = icona)
    let isLogoIcon = false;

        // Funzione per cambiare logo
    function toggleLogo() {
        isLogoIcon = !isLogoIcon;
        console.log('Cambio logo, nuovo stato:', isLogoIcon);

        if (isLogoIcon) {
            logoFull.style.display = 'none';
            logoIcon.style.display = 'block';
            logoIcon.style.visibility = 'visible';
            logoFull.style.visibility = 'hidden';
            console.log('Logo cambiato in icona');
        } else {
            logoFull.style.display = 'block';
            logoIcon.style.display = 'none';
            logoFull.style.visibility = 'visible';
            logoIcon.style.visibility = 'hidden';
            console.log('Logo cambiato in orizzontale');
        }
    }

    // Event listener per il click sul toggle
    toggleBtn.addEventListener('click', function(e) {
        console.log('Toggle cliccato!', e);
        setTimeout(toggleLogo, 100);
    });

    // Stato iniziale
    logoFull.style.display = 'block';
    logoIcon.style.display = 'none';
    console.log('Stato iniziale impostato');
});
