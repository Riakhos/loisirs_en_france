document.addEventListener("DOMContentLoaded", () => { 
    const dropdowns = document.querySelectorAll(".dropdown-menu");

    // Applique l'animation du dropdown
    dropdowns.forEach((dropdown) => {
        dropdown.addEventListener("show.bs.dropdown", () => {
            // Force reflow pour redéclencher l'animation
            dropdown.classList.remove("fadeIn");
            void dropdown.offsetWidth; // Reflow
            dropdown.classList.add("fadeIn");
        });
    });

    // Réinitialiser l'état de l'animation et des dropdowns lors du changement de page
    window.addEventListener('popstate', () => {
        dropdowns.forEach((dropdown) => {
            dropdown.classList.remove("fadeIn");
            void dropdown.offsetWidth; // Reflow pour réactiver l'animation
        });
    });

    // Re-déclenche l'animation si nécessaire au démarrage de chaque page
    if (document.body.contains(document.querySelector(".dropdown-menu.show"))) {
        dropdowns.forEach((dropdown) => {
            dropdown.classList.remove("fadeIn");
            void dropdown.offsetWidth; // Reflow
            dropdown.classList.add("fadeIn");
        });
    }
});
