<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\CourseComment;
use App\Entity\CourseImage;
use App\Entity\LessonDoc;
use App\Entity\User;
use App\Form\CourseCommentType;
use App\Form\CourseImageType;
use App\Repository\CourseAppointmentRepository;
use App\Repository\CourseRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tutorium", name="tutorium_")
 */
class TutoriumController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CourseRepository $courseRepository, CourseAppointmentRepository $appointmentRepository): Response
    {
        $courses = $courseRepository->findby([],['date' => 'DESC'],6);
        return $this->render('tutorial/index.html.twig', [
            'courses' => $courses,
            'appointments' => $appointmentRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $registry, CourseRepository $courseRepository, $id): Response
    {
        $course = $courseRepository->find($id);
        $nextCourse = $courseRepository->getNextPost($id);
        $prevCourse = $courseRepository->getPreviousPost($id);
        $comment = new CourseComment();
        $commentForm = $this->createForm(CourseCommentType::class,$comment);
        $commentForm->handleRequest($request);

        $courseImage = new CourseImage();
        $imageForm = $this->createForm(CourseImageType::class,$courseImage,[
            'action' => $this->generateUrl('course_image_upload',['id' => $id]),
            'method' => 'POST',
        ]);

        if($commentForm->isSubmitted() && $commentForm->isValid())
        {
            $user = new UserRepository($registry);
            $comment->setUser($user->find($this->getUser()));
            $comment->setCourse($course);
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('tutorium_show',['id' => $id]);
        }

        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('tutorial/show.html.twig', [
            'course' => $course,
            'next' => $nextCourse,
            'prev' => $prevCourse,
            'comment_form' => $commentForm->createView(),
            'upload_form' => $imageForm->createView()
        ]);
    }

    /**
     * @Route("/subscribe/{id}", name="subscribe")
     */
    public function subscribeToCourse(EntityManagerInterface $entityManager, Course $course, UserRepository $userRepository): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $userRepository->find($this->getUser());
        if($course->getUsers()->contains($user))
        {
            $course->removeUser($user);
            $this->addFlash('success','Kurs nicht mehr abonniert.');
        } else {
            $course->addUser($user);
            $this->addFlash('success','Kurs abonniert.');
        }
        $entityManager->persist($course);
        $entityManager->flush();
        return $this->redirectToRoute('tutorium_show',['id'=>$course->getId()]);
    }
}
