<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route; 
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

 #[Route(path: '/user', name: 'app_user')]
class UserController extends AbstractController
{
    #[Route(path: '/board', name: 'app_user_board')]
    public function boardIndex(#[CurrentUser] ?User $user): Response
    { 
        return $this->render('user/pets.html.twig');
    }

    #[Route(path: '/pets', name: 'app_user_pets')]
    public function petsIndex(#[CurrentUser] ?User $user): Response
    { 
        return $this->render('user/pets.html.twig');
    }
 
}
