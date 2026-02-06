<?php

namespace App\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Entity\PetType;

final class PetSubtypeController extends AbstractController
{
    #[Route('/api/petType/{id}', name: 'app_api_pet_subtype', methods: ['GET'])]
    public function index(EntityManagerInterface $em, SerializerInterface $serializer, int $id): JsonResponse
    {
        $petType = $em->getRepository(PetType::class)->find($id);
        if (!$petType) {
            return $this->json(['error' => 'Pet type not found'], 404);
        }
        
        $jsonContent = $serializer->serialize(
            $petType,
            'json',
            [
                'circular_reference_handler' => fn($object) => $object->getId(),
                'groups' => ['petType:read', 'petSubtype:read']
            ]
        );
 
        return JsonResponse::fromJsonString($jsonContent);

        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/Api/PetSubtypeController.php',
        //     'petSubtypes' => $petType,
        // ], Response::HTTP_OK, [], [
        //     AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
        //     "test" => ['pets']
        // ]);
    }
}
