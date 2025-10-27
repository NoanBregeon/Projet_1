import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Système de thème simplifié
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');

    if (themeToggle && themeIcon) {
        // Charger le thème sauvegardé
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            themeIcon.className = 'bi bi-sun-fill';
        }

        // Gérer le clic
        themeToggle.addEventListener('click', function() {
            const html = document.documentElement;

            if (html.getAttribute('data-theme') === 'dark') {
                html.removeAttribute('data-theme');
                themeIcon.className = 'bi bi-moon-fill';
                localStorage.setItem('theme', 'light');
            } else {
                html.setAttribute('data-theme', 'dark');
                themeIcon.className = 'bi bi-sun-fill';
                localStorage.setItem('theme', 'dark');
            }
        });
    }
});
