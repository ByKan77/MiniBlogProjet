<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Catégories
        $categoriesData = [
            ['name' => 'Actualités', 'description' => 'Les dernières actualités du blog'],
            ['name' => 'Tutoriels', 'description' => 'Guides et tutoriels pas à pas'],
            ['name' => 'Développement', 'description' => 'Articles sur le développement web'],
        ];
        $categories = [];
        foreach ($categoriesData as $data) {
            $category = new Category();
            $category->setName($data['name'])->setDescription($data['description']);
            $manager->persist($category);
            $categories[] = $category;
        }

        // Utilisateurs (mot de passe pour tous : password)
        $usersData = [
            ['email' => 'admin@example.com', 'firstname' => 'Admin', 'lastname' => 'Blog', 'roles' => ['ROLE_USER', 'ROLE_ADMIN']],
            ['email' => 'marie@example.com', 'firstname' => 'Marie', 'lastname' => 'Dupont', 'roles' => ['ROLE_USER']],
            ['email' => 'jean@example.com', 'firstname' => 'Jean', 'lastname' => 'Martin', 'roles' => ['ROLE_USER']],
        ];
        $users = [];
        foreach ($usersData as $data) {
            $user = new User();
            $user->setEmail($data['email'])
                ->setFirstname($data['firstname'])
                ->setLastname($data['lastname'])
                ->setRoles($data['roles'])
                ->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $manager->persist($user);
            $users[] = $user;
        }

        $manager->flush();

        // Posts
        $postsData = [
            ['title' => 'Bienvenue sur le blog', 'content' => "Ceci est le premier article du blog. Nous espérons que vous apprécierez votre visite et que vous reviendrez souvent pour découvrir nos nouveaux contenus.", 'category' => 0, 'author' => 0],
            ['title' => 'Premiers pas avec Symfony', 'content' => "Symfony est un framework PHP puissant. Dans cet article, nous verrons comment installer Symfony et créer notre première page.", 'category' => 1, 'author' => 0],
            ['title' => 'Les bonnes pratiques PHP 8', 'content' => "PHP 8 apporte de nombreuses améliorations : attributs, match, types stricts... Voici un tour d'horizon des bonnes pratiques.", 'category' => 2, 'author' => 1],
            ['title' => 'Créer un blog avec Doctrine', 'content' => "Doctrine ORM permet de gérer facilement les entités et les relations. Nous allons modéliser un blog avec des articles et des commentaires.", 'category' => 2, 'author' => 1],
            ['title' => 'Sécurité dans une application Symfony', 'content' => "La sécurité est essentielle : authentification, autorisation, CSRF, validation des entrées. Quelques rappels importants.", 'category' => 2, 'author' => 2],
        ];
        $posts = [];
        foreach ($postsData as $data) {
            $post = new Post();
            $post->setTitle($data['title'])
                ->setContent($data['content'])
                ->setPublishedAt(new \DateTimeImmutable('-' . rand(1, 30) . ' days'))
                ->setAuthor($users[$data['author']])
                ->setCategory($categories[$data['category']]);
            $manager->persist($post);
            $posts[] = $post;
        }

        $manager->flush();

        // Commentaires (quelques-uns approuvés, d'autres en attente)
        $commentsData = [
            ['content' => 'Super article, merci !', 'post' => 0, 'author' => 1, 'status' => 'approved'],
            ['content' => 'Très utile pour débuter.', 'post' => 0, 'author' => 2, 'status' => 'approved'],
            ['content' => 'J\'ai une question sur l\'installation...', 'post' => 1, 'author' => 2, 'status' => 'pending'],
            ['content' => 'Les attributs PHP 8 changent la donne.', 'post' => 2, 'author' => 0, 'status' => 'approved'],
            ['content' => 'Un autre commentaire de démo.', 'post' => 3, 'author' => 2, 'status' => 'approved'],
        ];
        foreach ($commentsData as $data) {
            $comment = new Comment();
            $comment->setContent($data['content'])
                ->setAuthor($users[$data['author']])
                ->setPost($posts[$data['post']])
                ->setStatus($data['status']);
            $manager->persist($comment);
        }

        $manager->flush();
    }
}
