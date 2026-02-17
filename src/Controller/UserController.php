<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/communaute', name: 'user_index')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }

    #[Route('/profil/{id}', name: 'user_profile')]
    public function profile(int $id): Response
    {
        return $this->render('user/profile.html.twig');
    }
}
