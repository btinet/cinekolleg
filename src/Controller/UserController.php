<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{

    public function getUserState(UserRepository $userRepository): Response
    {
        if($this->isGranted('ROLE_USER')){
            $user = $userRepository->find($this->getUser());
            if(!$user->isVerified()){
                $this->addFlash('warning','E-Mail-Adresse ist nicht verifiziert.');
            }
        }
        return $this->render('_blank.html.twig');
    }

}