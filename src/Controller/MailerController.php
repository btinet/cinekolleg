<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\User;
use App\Repository\LessonDocRepository;
use App\Repository\NotificationTemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;

/**
 * @Route("/email", name="email_")
 */
class MailerController extends AbstractController
{

    /**
     * @Route("/", name="send", methods={"POST"})
     * @param UserPasswordHasherInterface $passwordHasher
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param MailerInterface $mailer
     * @param LessonDocRepository $docRepository
     * @param DownloadHandler $downloadHandler
     * @return Response
     */
    public function sendEmail(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, Request $request, MailerInterface $mailer, NotificationTemplateRepository $templateRepository, LessonDocRepository $docRepository, DownloadHandler $downloadHandler): Response
    {
        $name = $request->request->get('first_name');
        $email = $request->request->get('email');
        $fileName = "cinekolleg_start_guide_{$name}.pdf";

        $document = $docRepository->find(7)->getDocument();



        $user = new User();
        $user->setFirstName($name);
        $user->setEmail($email);
        $user->setIsVerified(0);
        $user->setHasNewsletter(true);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                'BatteryHorseStaple'
            )
        );

        $notificationTemplates = $templateRepository->findAll();
        $notification = new Notification();
        $notification->setUser($user);

        foreach ($notificationTemplates as $notificationTemplate) {
            $notification->addSource($notificationTemplate);
        }

        $entityManager->persist($user);
        $entityManager->persist($notification);
        $entityManager->flush();

        $projectDir = $this->getParameter('kernel.project_dir');

        $sender = new Address('benjamin.wagner@cinekolleg.de','Benjamin Wagner | CineKolleg');

        $this->addFlash('success','E-Mail versandt!');

        $email = (new TemplatedEmail())
        ->from($sender)
        ->to($user->getEmail())
            ->bcc($sender)
        ->subject('Dein persönlicher Guide')
        ->context(['user' => $user])
        ->attachFromPath("{$projectDir}/var/storage/lessons/docs/{$document}")
        ->htmlTemplate('registration/newsletter_confirmation_email.html.twig')
        ;

    $mailer->send($email);

    return $this->redirectToRoute('app_index');
    }

}
