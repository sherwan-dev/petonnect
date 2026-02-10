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
use App\Repository\PetRepository;
use App\Service\FileUploader;
class PetController extends AbstractController
{

    public function __construct(
        private string $petsPicturesDir,
    ) {
    }

    #[Route(path: '/pet', name: 'app_pet')]
    public function petsIndex(
        #[CurrentUser] ?User $user,
        PetRepository $petRepository
    ): Response {
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $pets = $petRepository->findBy([
            'owner' => $user,
        ]);

        return $this->render('user/pet/pets.html.twig', [
            'pets' => $pets,
        ]);
    }

    #[Route(path: '/pet/new', name: 'app_pet_new')]
    public function createPet(
        Request $request,
        #[CurrentUser] ?User $user,
        EntityManagerInterface $entityManager,
        FileUploader $fileUploader
    ): Response {
        $pet = new Pet();
        $form = $this->createForm(PetType::class, $pet);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile) {
                $profilePictureFileName = $fileUploader->upload($profilePictureFile, $this->petsPicturesDir);
                $pet->setProfilePictureFileName($profilePictureFileName);
            }

            $pet->setOwner($this->getUser());
            $entityManager->persist($pet);
            $entityManager->flush();

            $this->addFlash('success', sprintf('%s has been added to your family!', $pet->getName()));
            return $this->redirectToRoute('app_pet');
        }

        return $this->render('user/pet/create_pet.html.twig', [
            'form' => $form
        ]);
    }
}
