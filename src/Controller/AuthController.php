<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AuthController extends AbstractController
{
    #[Route('/connexion', name: 'auth_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // Also provide a registration form on the login page so users can sign up there.
        $registrationUser = new User();
        $registrationForm = $this->createForm(RegistrationFormType::class, $registrationUser);

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'registrationForm' => $registrationForm->createView(),
        ]);
    }

    #[Route('/inscription', name: 'auth_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plain = $form->get('plainPassword')->getData();
            $hashed = $passwordHasher->hashPassword($user, $plain);
            $user->setPassword($hashed);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Compte créé — vous pouvez maintenant vous connecter.');

            return $this->redirectToRoute('auth_login');
        }

        // If form is not valid (or first display), render the login page and show the registration form there
        return $this->render('auth/login.html.twig', [
            'registrationForm' => $form->createView(),
            'last_username' => null,
            'error' => null,
        ]);
    }

    #[Route('/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony will intercept this route and handle the logout.
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
