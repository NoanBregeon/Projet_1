# Projet_1 - Application de gestion de cartes personnalisées

Ce projet est une application Laravel complète permettant de créer, modifier, visualiser et supprimer des cartes personnalisées. Chaque carte possède un nom, une description, une image, un logo/symbole et des couleurs thématiques. L'interface propose un aperçu en temps réel lors de la création ou modification d'une carte.

## Fonctionnalités principales

### 🎨 Gestion des cartes
- Ajout, modification et suppression de cartes
- Upload d'image et de logo pour chaque carte
- Choix des couleurs principales et secondaires (avec aperçu)
- Validation des champs et gestion des erreurs
- Aperçu dynamique de la carte pendant la saisie
- Suppression individuelle de l'image ou du logo
- Affichage de la collection sous forme de grille

### 🔐 Authentification & Sécurité
- Système d'inscription et de connexion
- Vérification d'email obligatoire pour les nouveaux comptes
- Réinitialisation de mot de passe par email
- Système de rôles avec Bouncer (Admin / Utilisateur)
- Permissions : seuls les admins peuvent modifier/supprimer les cartes

### 🌍 Interface moderne
- Thème sombre/clair avec transition fluide
- Support multilingue (Français/Anglais)
- Design responsive avec Bootstrap
- Header fixe avec contrôles d'accessibilité
- Animations et transitions CSS avancées

### 🏗️ Architecture
- Pattern Repository pour la gestion des données
- Services dédiés pour la logique métier
- Composants Blade réutilisables
- Middleware personnalisés pour les langues et rôles
- Système d'alertes contextuelles

## Configuration

### Email
Les emails sont configurés avec l'adresse : `test.dev@noanbregeon.com`
- Vérification d'email à l'inscription
- Reset de mot de passe
- Configuration Mailtrap pour les tests en développement

### Base de données
- MySQL avec migrations Laravel
- Système de rôles Bouncer intégré
- Stockage des sessions en base de données

## Utilisation

1. Accédez à l'application
2. Créez un compte (vérification email requise)
3. Connectez-vous pour ajouter vos cartes
4. Personnalisez-les avec images, couleurs et logos
5. Gérez votre collection facilement depuis l'interface web

### Rôles
- **Utilisateur** : Peut créer des cartes uniquement
- **Admin** : Peut modifier et supprimer toutes les cartes

## Technologies utilisées

- **Backend** : Laravel 11, PHP 8.2+
- **Frontend** : Bootstrap 5, CSS personnalisé, JavaScript vanilla
- **Base de données** : MySQL
- **Authentification** : Laravel Breeze + Email verification
- **Permissions** : Silber Bouncer
- **Emails** : Laravel Mail + Mailtrap (dev)

---

*Ce projet démontre une application Laravel moderne avec authentification complète, système de permissions, interface multilingue et design responsive.*
