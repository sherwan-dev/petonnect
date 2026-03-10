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
use Symfony\UX\Turbo\TurboBundle;

#[Route(path: '/user', name: 'app_user')]
class UserController extends AbstractController
{
    #[Route(path: '/board', name: 'board')]
    public function boardIndex(#[CurrentUser] ?User $user): Response
    {
        return $this->render('user/board.html.twig');
    }

    #[Route(path: '/active-pet/{id}', name: '_active_pet', methods: ['POST'])]
    public function setActivePet(Request $request, #[CurrentUser] ?User $user, Pet $pet, EntityManagerInterface $entityManager): Response
    {
        if ($pet->getOwner() === $user) {
            $user->setActivePet($pet);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Active pet updated successfully!');
        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->render('ui-component/_flashes.html.twig');
        }
        return $this->redirectToRoute('app_home');
    }

}
