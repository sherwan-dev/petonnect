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

class PetController extends AbstractController
{

    #[Route(path: '/pet', name: 'app_pet')]
    public function petsIndex(#[CurrentUser] ?User $user): Response
    { 
        return $this->render('user/pet/pets.html.twig');
    } 

    #[Route(path: '/pet/new', name: 'app_pet_new')]
    public function createPet(
        Request $request, 
        #[CurrentUser] ?User $user,
        EntityManagerInterface $entityManager): Response
    {
        $pet = new Pet();
        $form = $this->createForm(PetType::class, $pet, [
            'attr' => [
                'class' => 'flex flex-col gap-4',
            ],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pet->setOwner($this->getUser());
            $entityManager->persist($pet);
            $entityManager->flush();
        }


        return $this->render('user/pet/create_pet.html.twig', [
            'form' => $form->createView(),
        ]);
    } 
}
