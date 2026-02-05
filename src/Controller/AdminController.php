<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_index')]
    public function index(PostRepository $postRepository, UserRepository $userRepository, CommentRepository $commentRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'posts_count' => count($postRepository->findAll()),
            'users_count' => count($userRepository->findAll()),
            'comments_count' => count($commentRepository->findAll()),
            'pending_comments_count' => count($commentRepository->findBy(['status' => 'pending'])),
        ]);
    }

    #[Route('/users', name: 'app_admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/users/{id}/toggle', name: 'app_admin_user_toggle', methods: ['POST'])]
    public function toggleUser(User $user, EntityManagerInterface $entityManager): Response
    {
        // Désactiver un utilisateur en retirant son rôle USER
        if (in_array('ROLE_USER', $user->getRoles())) {
            $roles = $user->getRoles();
            $roles = array_diff($roles, ['ROLE_USER']);
            $user->setRoles($roles);
        } else {
            // Réactiver en ajoutant ROLE_USER
            $roles = $user->getRoles();
            if (!in_array('ROLE_USER', $roles)) {
                $roles[] = 'ROLE_USER';
            }
            $user->setRoles($roles);
        }

        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur modifié avec succès');

        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/posts', name: 'app_admin_posts')]
    public function posts(PostRepository $postRepository): Response
    {
        return $this->render('admin/posts.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    #[Route('/comments', name: 'app_admin_comments')]
    public function comments(CommentRepository $commentRepository): Response
    {
        return $this->render('admin/comments.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }

    #[Route('/comments/{id}/approve', name: 'app_admin_comment_approve', methods: ['POST'])]
    public function approveComment(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $comment->setStatus('approved');
        $entityManager->flush();

        $this->addFlash('success', 'Commentaire approuvé');

        return $this->redirectToRoute('app_admin_comments');
    }

    #[Route('/comments/{id}/reject', name: 'app_admin_comment_reject', methods: ['POST'])]
    public function rejectComment(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $comment->setStatus('rejected');
        $entityManager->flush();

        $this->addFlash('success', 'Commentaire rejeté');

        return $this->redirectToRoute('app_admin_comments');
    }
}
