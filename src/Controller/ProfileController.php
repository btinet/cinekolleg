<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\NotificationTemplate;
use App\Form\NotificationType;
use App\Form\UserType;
use App\Repository\NotificationRepository;
use App\Repository\NotificationTemplateRepository;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $registry, UserRepository $userRepository, NotificationRepository $notificationRepository, NotificationTemplateRepository $templateRepository): Response
    {

        $notification = new Notification();
        $userNotification = $notificationRepository->findBy(['user' => $this->getUser()]);
        if($userNotification){
          $notification = array_pop($userNotification);
        }
        $notificationForm = $this->createForm(NotificationType::class,$notification);
        $notificationForm->handleRequest($request);

        $userData = $userRepository->find($this->getUser());

        $userForm = $this->createForm(UserType::class,$userData);
        $userForm->handleRequest($request);

        if($userForm->isSubmitted() && $userForm->isValid())
        {
            $userData = $userForm->getData();
            $entityManager->persist($userData);
            $entityManager->flush();
            $this->addFlash('success','Kontodaten aktualisiert.');
        }

        if($notificationForm->isSubmitted() && $notificationForm->isValid())
        {
            $user = new UserRepository($registry);
            $notification->setUser($user->find($this->getUser()));
            $notification = $notificationForm->getData();
            $entityManager->persist($notification);
            $entityManager->flush();
            $this->addFlash('success','Einstellungen gespeichert.');
        }

        return $this->render('profile/index.html.twig',[
            'user' => $this->userRepository->find($this->getUser()),
            'templates' => $templateRepository->findAll(),
            'user_form' => $userForm->createView(),
            'notification_form' => $notificationForm->createView(),

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
