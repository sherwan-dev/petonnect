<?php 
namespace App\Controller\Api;

use App\Entity\Pet;
use App\Entity\PetFollow;
use App\Repository\PetFollowRepository;
use App\Repository\PetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/pets')]
// #[IsGranted('IS_AUTHENTICATED_FULLY')] 
class FollowController extends AbstractController
{
    #[Route('/{id}/follow', name: 'api_pet_follow', methods: ['POST'])]
    public function follow(
        int $id,
        Request $request,
        PetRepository $petRepo,
        EntityManagerInterface $em
    ): JsonResponse {
        
        $payload = $request->getPayload();
        $followerId = $payload->get('follower_id');

        if (!$followerId) {
            return $this->json(['error' => 'follower_id is required'], 400);
        }

        $targetPet = $petRepo->find($id); 
        $myPet = $petRepo->find($followerId); 

        if (!$targetPet || !$myPet) {
            return $this->json(['error' => 'Pet not found'], 404);
        }

        if ($myPet->getOwner() !== $this->getUser()) {
            return $this->json(['error' => 'You do not own this pet'], 403);
        }

        if ($myPet === $targetPet) {
            return $this->json(['error' => 'Pets cannot follow themselves'], 400);
        }

        $existingFollow = $em->getRepository(PetFollow::class)->findOneBy([
            'follower' => $myPet,
            'followed' => $targetPet
        ]);

        if ($existingFollow) {
            return $this->json(['message' => 'Already following'], 200);
        }

        $follow = new PetFollow();
        $follow->setFollower($myPet);
        $follow->setFollowed($targetPet);
        
        $em->persist($follow);
        $em->flush();

        return $this->json(['message' => 'Followed successfully'], 201);
    }

    
#[Route('/{id}/follow', name: 'api_pet_unfollow', methods: ['DELETE'])]
    public function unfollow(
        int $id, 
        Request $request,
        PetRepository $petRepo,
        EntityManagerInterface $em
    ): JsonResponse {
        $payload = $request->getPayload();
        $followerId = $payload->get('follower_id');

        if (!$followerId) {
            return $this->json(['error' => 'follower_id is required'], 400);
        }

        $targetPet = $petRepo->find($id);
        $myPet = $petRepo->find($followerId);
        
        if (!$targetPet || !$myPet) {
             return $this->json(['error' => 'Pet not found'], 404);
        }

        if ($myPet->getOwner() !== $this->getUser()) {
            return $this->json(['error' => 'You do not own this pet'], 403);
        }

        $followRecord = $em->getRepository(PetFollow::class)->findOneBy([
            'follower' => $myPet,
            'followed' => $targetPet
        ]);

        if (!$followRecord) {
             return $this->json(['message' => 'Not following'], 404);
        }

        $em->remove($followRecord);
        $em->flush();

        return $this->json(['message' => 'Unfollowed successfully'], 200);
    }

}