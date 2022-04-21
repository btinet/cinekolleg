<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile", name="profile_")
 */
class ProfileController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private UserPasswordHasherInterface $passwordHaser;
    private UserRepository $userRepository;

    public function __construct(UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository, EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
        $this->passwordHaser = $passwordHasher;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('profile/index.html.twig',[
            'user' => $this->userRepository->find($this->getUser())
        ]);
    }

    /**
     * @Route("/verify_email", name="verify_email")
     */
    public function sendConfirmationMail()
    {
        $user = $this->userRepository->find($this->getUser());
        if(!$user->isVerified())
        {
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('benjamin.wagner@cinekolleg.de', 'Benjamin Wagner | CineKolleg'))
                    ->to($user->getEmail())
                    ->subject('Bitte bestätige Deine E-Mail-Adresse')
                    ->context(['user' => $user])
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            $this->addFlash('success','E-Mail zur Bestätigung versandt.');
        } else {
            $this->addFlash('warning','E-Mail ist bereits bestätigt.');
        }
        return $this->redirectToRoute('profile_index');
    }

}
