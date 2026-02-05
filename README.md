# Blog Mini - Projet Symfony

Un blog complet développé avec Symfony qui permet de gérer des articles, des commentaires et des utilisateurs avec différents niveaux d'accès.

## Fonctionnalités

- **Gestion des articles** : Création, modification et suppression d'articles avec catégories
- **Système de commentaires** : Les utilisateurs peuvent commenter les articles (validation admin)
- **Gestion des utilisateurs** : Inscription, connexion avec rôles (Admin/User)
- **Interface d'administration** : Dashboard pour gérer articles, utilisateurs et commentaires
- **Design responsive** : Interface Bootstrap moderne et adaptée mobile

## Installation

### Prérequis

- PHP 8.2 ou supérieur
- Composer
- MySQL/MariaDB
- Symfony CLI (optionnel)

### Étapes

1. Cloner le projet :
```bash
git clone https://github.com/ByKan77/MiniBlogProjet.git
cd MiniBlogProjet
```

2. Installer les dépendances :
```bash
composer install
```

3. Configurer la base de données dans `.env` :
```env
DATABASE_URL="mysql://root:@127.0.0.1:3307/Blogmini?serverVersion=10.4.32-MariaDB&charset=utf8mb4"
```

4. Créer la base de données et exécuter les migrations :
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. Lancer le serveur :
```bash
symfony serve
# ou
php -S localhost:8000 -t public
```

6. Accéder à l'application : `http://localhost:8000`

## Structure du projet

- `src/Controller/` : Contrôleurs (Post, Admin, Comment, etc.)
- `src/Entity/` : Entités Doctrine (User, Post, Comment, Category)
- `src/Form/` : Formulaires Symfony
- `templates/` : Templates Twig avec Bootstrap
- `migrations/` : Migrations de base de données

## Utilisation

### Créer un compte admin

Après avoir créé un utilisateur normal, ajoutez le rôle `ROLE_ADMIN` dans la base de données ou via phpMyAdmin.

### Catégories

Quelques catégories sont créées par défaut : Actualités, Technologie, Lifestyle, Tutoriels.

## Technologies utilisées

- Symfony 7.4
- Doctrine ORM
- Twig
- Bootstrap 5
- MySQL/MariaDB
