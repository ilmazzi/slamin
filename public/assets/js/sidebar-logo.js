// Gestione logo responsive per sidebar collassata


document.addEventListener('DOMContentLoaded', function() {
    

    const logoFull = document.querySelector('.logo-full');
    const logoIcon = document.querySelector('.logo-icon');
    const toggleBtn = document.querySelector('.header-toggle');

    
    
    

    if (!logoFull || !logoIcon || !toggleBtn) {
        
        return;
    }

    

    // Stato del logo (false = orizzontale, true = icona)
    let isLogoIcon = false;

        // Funzione per cambiare logo
    function toggleLogo() {
        isLogoIcon = !isLogoIcon;
        

        if (isLogoIcon) {
            logoFull.style.display = 'none';
            logoIcon.style.display = 'block';
            logoIcon.style.visibility = 'visible';
            logoFull.style.visibility = 'hidden';
            
        } else {
            logoFull.style.display = 'block';
            logoIcon.style.display = 'none';
            logoFull.style.visibility = 'visible';
            logoIcon.style.visibility = 'hidden';
            
        }
    }

    // Event listener per il click sul toggle
    toggleBtn.addEventListener('click', function(e) {
        
        setTimeout(toggleLogo, 100);
    });

    // Stato iniziale
    logoFull.style.display = 'block';
    logoIcon.style.display = 'none';
    
});
