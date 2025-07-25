// JavaScript pour le thème par défaut

document.addEventListener('DOMContentLoaded', function() {
    console.log('MonFramework - Thème par défaut chargé');
    
    // Exemple d'interaction simple
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Animation simple au clic
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);
        });
    });
});

