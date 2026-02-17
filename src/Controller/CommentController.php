<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommentController extends AbstractController
{
    #[Route('/mes-commentaires', name: 'comment_index')]
    public function index(): Response
    {
        return $this->render('comment/index.html.twig');
    }

    #[Route('/commentaire/{id}/editer', name: 'comment_edit')]
    public function edit(int $id): Response
    {
        return $this->render('comment/edit.html.twig');
    }
}
