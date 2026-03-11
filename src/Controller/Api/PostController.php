<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\Turbo\TurboBundle;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api', name: 'app_api_post')]
class PostController extends AbstractController
{

    // #[Route('/post/{id}', name: '_delete', methods: ['DELETE'])]
    // public function delete(EntityManagerInterface $em, PostRepository $postRepository, int $id): JsonResponse
    // {
    //     $post = $postRepository->find($id);

    //     if (!$post) {
    //         return new JsonResponse(['error' => 'Post not found'], Response::HTTP_NOT_FOUND);
    //     }

    //     $em->remove($post);
    //     $em->flush();

    //     return new JsonResponse(['message' => 'Post deleted successfully']);
    // } 

    #[Route('/post/{id}', name: '_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $em, Post $post, Request $request): Response
    {
  return new Response(
                sprintf(
                    '<turbo-stream action="remove" target="post-card-%s"></turbo-stream>',
                    46
                ),
                200,
                ['Content-Type' => TurboBundle::STREAM_MEDIA_TYPE]
            );
        // if ($post->getAuthor()->getOwner() !== $this->getUser()) {
        //     return new JsonResponse(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        // }

        // $postId = $post->getId(); 
        // $em->remove($post);
        // $em->flush();

        // if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
          
        // }

        // return new JsonResponse(['message' => 'Post deleted successfully']);
    }

}