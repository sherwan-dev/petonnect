<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route; 
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Form\PetType;
use App\Entity\Pet;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

 #[Route(path: '/user', name: 'app_user_')]
class UserController extends AbstractController
{
    #[Route(path: '/board', name: 'board')]
    public function boardIndex(#[CurrentUser] ?User $user): Response
    { 
        return $this->render('user/board.html.twig');
    }
}
