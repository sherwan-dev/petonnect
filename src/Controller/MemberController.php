<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route; 
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Repository\PetRepository;
use App\Repository\PetFollowRepository;

class MemberController extends AbstractController
{

    #[Route(path: '/members', name: 'board')]
    public function boardIndex(
        #[CurrentUser] ?User $user,
        PetRepository $petRepository,
        PetFollowRepository $petFollowRepository
    ): Response
    { 
        $filters = [];
        $followerPet = null;
        $followedPetIds = [];

        if ($user) {
            $filters[] = ['owner', '!=', $user];
            if (!$user->getPets()->isEmpty()) {
                $followerPet = $user->getPets()->first();//TODO: handle multiple pets per user (active pet selection)
            }
        }

        $pets = $petRepository->findByFilters($filters);

        if ($followerPet && !empty($pets)) {
            $targetIds = array_map(static fn($pet) => $pet->getId(), $pets);

            $rows = $petFollowRepository
                ->createQueryBuilder('pf')
                ->select('IDENTITY(pf.followed) AS followedId')
                ->where('pf.follower = :follower')
                ->andWhere('pf.followed IN (:targets)')
                ->setParameter('follower', $followerPet)
                ->setParameter('targets', $targetIds)
                ->getQuery()
                ->getScalarResult();

            $followedPetIds = array_map(static fn(array $row) => (int) $row['followedId'], $rows);
        }

        return $this->render('member/index.html.twig', [
            'pets' => $pets,
            'followerPet' => $followerPet,
            'followedPetIds' => $followedPetIds,
        ]);
    }

     #[Route(path: '/members/{id}', name: 'app_member_profile')]
     public function membersIndex(#[CurrentUser] ?User $user, int $id): Response
     { 

         return $this->render('member/one_member/index.html.twig');
     }


}