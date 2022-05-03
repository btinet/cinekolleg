<?php

namespace App\Controller;

use App\Entity\CourseImage;
use App\Form\CourseImageType;
use App\Repository\CourseRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tutorium/user_image", name="course_image_")
 */
class CourseImageController extends AbstractController
{
    /**
     * @Route("/upload/{id}", name="upload", methods={"POST"})
     */
    public function upload($id, CourseRepository $courseRepository, UserRepository $userRepository, EntityManagerInterface $entityManager, Request $request)
    {
        $course = $courseRepository->find($id);
        $user = $userRepository->find($this->getUser());
        $courseImage = new CourseImage();

        $imageForm = $this->createForm(CourseImageType::class,$courseImage,[
            'action' => $this->generateUrl('course_image_upload',['id' => $id]),
            'method' => 'POST',
        ]);
        $imageForm->handleRequest($request);

        if($imageForm->isValid() && $imageForm->isSubmitted()) {
            $courseImage->setCourse($course);
            $courseImage->setUser($user);
            $courseImage = $imageForm->getData();
            $entityManager->persist($courseImage);
            $entityManager->flush();
            $this->addFlash('success','Upload war erfolgreich!');
        } else {
            $this->addFlash('danger','Es sind nur Bilder erlaubt.');
        }

        return $this->redirectToRoute('tutorium_show',['id' => $course->getId()]);
    }

}