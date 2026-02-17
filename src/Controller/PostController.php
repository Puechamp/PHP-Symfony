<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController
{
    #[Route('/', name: 'post_index')]
    #[Route('/posts', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    #[Route('/post/nouveau', name: 'post_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            $post->setAuthor($user);

            $em->persist($post);
            $em->flush();

            $this->addFlash('success', 'Post créé avec succès.');
            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        return $this->render('post/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/{id}', name: 'post_show')]
    public function show(int $id): Response
    {
        return $this->render('post/show.html.twig');
    }

    #[Route('/post/{id}/editer', name: 'post_edit')]
    public function edit(int $id): Response
    {
        return $this->render('post/edit.html.twig');
    }
}
