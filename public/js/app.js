// Système de thème avec transition fluide globale
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');

    if (themeToggle && themeIcon) {
        // Vérifier le thème déjà appliqué
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        if (isDark) {
            themeIcon.className = 'bi bi-sun-fill';
        }

        // Gérer le clic avec animation douce
        themeToggle.addEventListener('click', function() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');

            // Ajouter l'animation subtile au bouton
            themeToggle.classList.add('theme-changing');

            if (currentTheme === 'dark') {
                // Passage au thème clair
                html.removeAttribute('data-theme');
                html.style.backgroundColor = '#f8fafc';
                html.style.color = '#0f1724';
                themeIcon.className = 'bi bi-moon-fill';
                localStorage.setItem('theme', 'light');
            } else {
                // Passage au thème sombre
                html.setAttribute('data-theme', 'dark');
                html.style.backgroundColor = '#1e293b';
                html.style.color = '#f8fafc';
                themeIcon.className = 'bi bi-sun-fill';
                localStorage.setItem('theme', 'dark');
            }

            // Supprimer l'animation après 800ms
            setTimeout(() => {
                themeToggle.classList.remove('theme-changing');
            }, 800);
        });
    }
});
