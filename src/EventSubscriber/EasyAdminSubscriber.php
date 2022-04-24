<?php

namespace App\EventSubscriber;

use App\Entity\Course;
use App\Entity\User;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityBuiltEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private UserPasswordHasherInterface $passwordHasher;
    protected MailerInterface $mailer;
    private array $emails;
    private UserRepository $userRepository;
    private NotificationRepository $notificationRepository;

    public function __construct(UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer, UserRepository $userRepository, NotificationRepository $notificationRepository)
    {
        $this->passwordHasher = $passwordHasher;
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
        $this->notificationRepository = $notificationRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['setPassword'],
            AfterEntityUpdatedEvent::class => ['sendCourseUpdate'],
            AfterEntityPersistedEvent::class => ['sendCourseCreate'],
        ];
    }

    public function setPassword(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }

        $hashedPassword = $this->passwordHasher->hashPassword($entity,$entity->getPlainPassword());
        $entity->setPassword($hashedPassword);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendCourseUpdate(AfterEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if(!($entity instanceof Course)) {
            return;
        }

        $sender = new Address('benjamin.wagner@cinekolleg.de','Benjamin Wagner | CineKolleg');

        foreach ($entity->getUsers() as $user) {
            $notification = $this->notificationRepository->findNotificationSettingsByUser($user,'3002');

            if(null !== $notification) {
                $this->emails[] = (new TemplatedEmail())
                    ->from($sender)
                    ->to(new Address($user->getEmail(), $user->getFirstName()))
                    ->subject("Kurs aktualisiert")
                    ->context(['user' => $user, 'course' => $entity])
                    ->htmlTemplate('emails/email_course_update.html.twig');
            }
        }
        if(!empty($this->emails)) {
            foreach ($this->emails as $email) {
                $this->mailer->send($email);
            }
        }

    }

    public function sendCourseCreate(AfterEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if(!($entity instanceof Course)) {
            return;
        }

        $sender = new Address('benjamin.wagner@cinekolleg.de','Benjamin Wagner | CineKolleg');

        foreach ($this->userRepository->findAll() as $user) {
            $notification = $this->notificationRepository->findNotificationSettingsByUser($user,'3001');

            if(null !== $notification)
            {
                $this->emails[] = (new TemplatedEmail())
                    ->from($sender)
                    ->to(new Address($user->getEmail(), $user->getFirstName()))
                    ->subject("Neuer Kurs verfügbar")
                    ->context(['user' => $user,'course'=>$entity])
                    ->htmlTemplate('emails/email_course_create.html.twig');
            }
        }
        if(!empty($this->emails)) {
            foreach ($this->emails as $email) {
                $this->mailer->send($email);
            }
        }
    }

}