# Projet_1 - Application de gestion de cartes personnalisées

Ce projet est une application Laravel permettant de créer, modifier, visualiser et supprimer des cartes personnalisées. Chaque carte possède un nom, une description, une image, un logo/symbole et des couleurs thématiques. L'interface propose un aperçu en temps réel lors de la création ou modification d'une carte.

## Fonctionnalités principales

- Ajout, modification et suppression de cartes
- Upload d'image et de logo pour chaque carte
- Choix des couleurs principales et secondaires (avec aperçu)
- Validation des champs et gestion des erreurs
- Aperçu dynamique de la carte pendant la saisie
- Suppression individuelle de l'image ou du logo
- Affichage de la collection sous forme de grille

## Installation rapide

1. Cloner le projet et installer les dépendances (`composer install`, `npm install`)
2. Configurer le fichier `.env` et la base de données
3. Lancer les migrations (`php artisan migrate`)
4. Créer le lien de stockage pour les images (`php artisan storage:link`)
5. Démarrer le serveur local (`php artisan serve`)

## Utilisation

Accédez à l'application, ajoutez vos cartes, personnalisez-les et gérez votre collection facilement depuis l'interface web.

---

*Ce projet est un exemple pédagogique pour apprendre Laravel et la gestion de fichiers et formulaires avancés.*
