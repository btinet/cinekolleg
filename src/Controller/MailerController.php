<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\LessonDocRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
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
    public function sendEmail(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, Request $request, MailerInterface $mailer,LessonDocRepository $docRepository, DownloadHandler $downloadHandler): Response
    {
        $name = $request->request->get('first_name');
        $email = $request->request->get('email');
        $fileName = "cinekolleg_start_guide_{$name}.pdf";

        $document = $docRepository->find(4)->getDocument();


        $user = new User();
        $user->setFirstName($name);
        $user->setEmail($email);
        $user->setIsVerified(0);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                'BatteryHorseStaple'
            )
        );

        $entityManager->persist($user);
        $entityManager->flush();

        $projectDir = $this->getParameter('kernel.project_dir');

        $sender = new Address('benjamin.wagner@cinekolleg.de','Benjamin Wagner | CineKolleg');

        $email = (new TemplatedEmail())
        ->from($sender)
        ->to($user->getEmail())
        ->subject('Dein persönlicher Guide')
        ->context(['user' => $user])
        ->attachFromPath("{$projectDir}/var/storage/lessons/docs/{$document}")
        ->htmlTemplate('registration/newsletter_confirmation_email.html.twig')
        ;

    $mailer->send($email);
    $this->addFlash('success','E-Mail versandt!');
    return $this->redirectToRoute('app_index');
    }

}
