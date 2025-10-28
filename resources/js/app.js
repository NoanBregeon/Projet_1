import 'bootstrap';
import $ from 'jquery';

// Rendre jQuery global pour compatibilité
window.jQuery = window.$ = $;

// Système de thème avec jQuery
$(document).ready(function() {
    const $themeToggle = $('#theme-toggle');
    const $themeIcon = $('#theme-icon');
    const $html = $('html');
    
    if ($themeToggle.length && $themeIcon.length) {
        // Vérifier le thème déjà appliqué au chargement
        const isDark = $html.attr('data-theme') === 'dark';
        if (isDark) {
            $themeIcon.removeClass('bi-moon-fill').addClass('bi-sun-fill');
        }
        
        // Gérer le clic avec animation fluide jQuery
        $themeToggle.on('click', function() {
            const currentTheme = $html.attr('data-theme');
            
            // Animation du bouton
            $(this).addClass('theme-changing');
            
            if (currentTheme === 'dark') {
                // Passage au thème clair avec animation jQuery
                $html.removeAttr('data-theme')
                     .css({
                         'background-color': '#f8fafc',
                         'color': '#0f1724'
                     });
                
                $themeIcon.removeClass('bi-sun-fill').addClass('bi-moon-fill');
                localStorage.setItem('theme', 'light');
                
            } else {
                // Passage au thème sombre avec animation jQuery
                $html.attr('data-theme', 'dark')
                     .css({
                         'background-color': '#1e293b',
                         'color': '#f8fafc'
                     });
                
                $themeIcon.removeClass('bi-moon-fill').addClass('bi-sun-fill');
                localStorage.setItem('theme', 'dark');
            }
            
            // Supprimer la classe d'animation après 800ms
            setTimeout(() => {
                $(this).removeClass('theme-changing');
            }, 800);
        });
    }
    
    // Gestion des favoris avec jQuery et CSRF token
    $('.favorite-btn').on('click', function(e) {
        e.preventDefault();
        
        const $btn = $(this);
        const universId = $btn.data('univers-id');
        const $icon = $btn.find('i');
        const $text = $btn.find('.favorite-text');
        const $card = $btn.closest('.col-md-6, .col-lg-4');
        
        // Animation du bouton
        $btn.prop('disabled', true).addClass('loading');
        
        $.ajax({
            url: `/favorites/${universId}/toggle`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    // Mettre à jour l'icône et le texte
                    if (response.is_favorite) {
                        $icon.removeClass('bi-heart').addClass('bi-heart-fill');
                        $text.text($('html').attr('lang') === 'en' ? 'Favorited' : 'Favori');
                        $btn.removeClass('btn-outline-warning').addClass('btn-warning');
                    } else {
                        $icon.removeClass('bi-heart-fill').addClass('bi-heart');
                        $text.text($('html').attr('lang') === 'en' ? 'Favorite' : 'Favoris');
                        $btn.removeClass('btn-warning').addClass('btn-outline-warning');
                        
                        // Si on est sur la page des favoris, retirer la carte avec animation
                        if ($('body').hasClass('favorites-page')) {
                            $card.fadeOut(500, function() {
                                $(this).remove();
                                // Vérifier s'il reste des cartes
                                if ($('.row .col-md-6, .row .col-lg-4').length === 0) {
                                    location.reload();
                                }
                            });
                        }
                    }
                    
                    // Mettre à jour le badge dans le header
                    updateFavoritesCount();
                    
                    // Animation de succès
                    if (!$('body').hasClass('favorites-page') || response.is_favorite) {
                        $btn.addClass('btn-success');
                        setTimeout(() => {
                            $btn.removeClass('btn-success loading');
                        }, 1000);
                    }
                    
                    // Toast notification
                    showToast(response.message, 'success');
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Erreur lors de la mise à jour des favoris';
                showToast(errorMsg, 'error');
                $btn.removeClass('loading');
            },
            complete: function() {
                if (!$('body').hasClass('favorites-page')) {
                    $btn.prop('disabled', false);
                }
            }
        });
    });
    
    // Animation d'entrée pour les cartes avec jQuery
    $('.card').each(function(index) {
        $(this).css({
            'opacity': '0',
            'transform': 'translateY(20px)'
        }).delay(index * 100).animate({
            'opacity': '1'
        }, 600, function() {
            $(this).css('transform', 'translateY(0)');
        });
    });
    
    // Amélioration des tooltips Bootstrap avec jQuery
    $('[data-bs-toggle="tooltip"]').each(function() {
        new bootstrap.Tooltip(this);
    });
    
    // Animation fluide pour les alertes
    $('.alert').hide().fadeIn(500);
    
    // Auto-hide des alertes après 5 secondes
    $('.alert').delay(5000).fadeOut(500);
    
    // Marquer la page des favoris
    if (window.location.pathname.includes('/favorites')) {
        $('body').addClass('favorites-page');
    }
});

// Fonctions utilitaires jQuery
$(window).on('load', function() {
    // Animation smooth scroll pour les ancres
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 600);
        }
    });
});

// Fonction pour mettre à jour le compteur de favoris
function updateFavoritesCount() {
    $.get('/favorites/count', function(data) {
        $('.header-controls .badge').text(data.count);
    }).fail(function() {
        console.warn('Impossible de mettre à jour le compteur de favoris');
    });
}

// Fonction pour afficher les toasts
function showToast(message, type) {
    const toastClass = type === 'success' ? 'bg-success' : 'bg-danger';
    const toast = $(`
        <div class="toast align-items-center text-white ${toastClass} border-0" role="alert" data-bs-autohide="true" data-bs-delay="3000">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `);
    
    if (!$('.toast-container').length) {
        $('body').append('<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1100;"></div>');
    }
    
    $('.toast-container').append(toast);
    const bsToast = new bootstrap.Toast(toast[0]);
    bsToast.show();
    
    // Nettoyer après fermeture
    toast.on('hidden.bs.toast', function() {
        $(this).remove();
    });
}

// Amélioration de l'UX avec jQuery
$(document).on('click', '.btn', function() {
    // Effet ripple sur les boutons
    const $button = $(this);
    if ($button.find('.ripple').length === 0) {
        const ripple = $('<span class="ripple"></span>');
        $button.addClass('position-relative overflow-hidden');
        $button.append(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }
});
