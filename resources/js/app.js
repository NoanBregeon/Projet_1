import './bootstrap';
import Alpine from 'alpinejs';

// Initialiser Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Système de thème
function toggleTheme() {
    const html = document.documentElement;
    const themeIcon = document.getElementById('theme-icon');

    console.log('Toggle theme clicked'); // Debug

    if (html.getAttribute('data-theme') === 'dark') {
        html.removeAttribute('data-theme');
        if (themeIcon) themeIcon.className = 'bi bi-moon-fill';
        localStorage.setItem('theme', 'light');
        console.log('Switched to light theme'); // Debug
    } else {
        html.setAttribute('data-theme', 'dark');
        if (themeIcon) themeIcon.className = 'bi bi-sun-fill';
        localStorage.setItem('theme', 'dark');
        console.log('Switched to dark theme'); // Debug
    }
}

// Charger le thème au démarrage et attacher l'événement
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme');
    const themeIcon = document.getElementById('theme-icon');
    const themeToggle = document.getElementById('theme-toggle');

    console.log('Loading saved theme:', savedTheme); // Debug

    // Charger le thème sauvegardé
    if (savedTheme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
        if (themeIcon) themeIcon.className = 'bi bi-sun-fill';
    }

    // Attacher l'événement de clic au bouton
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
        console.log('Theme toggle event attached'); // Debug
    }
});
