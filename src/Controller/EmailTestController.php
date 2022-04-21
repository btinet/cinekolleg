<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/email", name="email_")
 */
class EmailTestController extends AbstractController
{

    /**
     * @Route("/register/confirm", name="register_confirm")
     */
    public function registerConfirm(UserRepository $userRepository): Response
    {
        $user = $userRepository->find(1);

        return $this->render('registration/confirmation_email.html.twig', [
            'user' => $user,
            'signedUrl' => $this->generateUrl('app_index'),
        ]);
    }

    /**
     * @Route("/newsletter/confirm", name="register_confirm")
     */
    public function newsletterConfirm(UserRepository $userRepository): Response
    {
        $user = $userRepository->find(1);

        return $this->render('registration/newsletter_confirmation_email.html.twig', [
            'user' => $user,
            'signedUrl' => $this->generateUrl('app_index'),
        ]);
    }

    /**
     * @Route("/password/request", name="password_request")
     */
    public function passwordRequest(UserRepository $userRepository): Response
    {
        $user = $userRepository->find(1);

        return $this->render('reset_password/email.html.twig', [
            'user' => $user,
            'signedUrl' => $this->generateUrl('app_index'),
            'resetToken' => ['token' => 'abc']
        ]);
    }

}