<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;
use App\Entity\PostImage;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\FileUploader;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/post', name: 'app_post')]
class PostController extends AbstractController
{
    public function __construct(
        private string $postsImagesDir
    ) {
    }
    
    #[Route('/new', name: '_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $em,
        FileUploader $fileUploader): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($this->getUser()->getPets()->first());//TODO: Handle active pet

            $imageFiles = $form->get('imageFiles')->getData();
            if ($imageFiles) {
                foreach ($imageFiles as $imageFile) {
                    $profilePictureFileName = $fileUploader->upload($imageFile, $this->postsImagesDir);
                    $postImage = new PostImage();
                    $postImage->setImageFilename($profilePictureFileName);
                    $post->addImage($postImage);
                }
            }

            $em->persist($post);
            $em->flush();

            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                return $this->renderBlock('post/_create_success.stream.html.twig','new_post', [
                    'post' => $post,
                    'form' => $this->createForm(PostType::class)
                ]);
            }
            
            return $this->redirectToRoute('app_home');
        }

        return $this->render('post/_create.html.twig', [
            'form' => $form,
        ], new Response(422));
    }

    
    #[Route('/{id}', name: '_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $em, Post $post, Request $request): Response
    {

        if ($post->getAuthor()->getOwner() !== $this->getUser()) {
            return new JsonResponse(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $postId = $post->getId(); 
        $em->remove($post);
        $em->flush();

        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
            return new Response(
                sprintf(
                    '<turbo-stream action="remove" target="post-card-%s"></turbo-stream>',
                    $postId
                ),
                200,
                ['Content-Type' => TurboBundle::STREAM_MEDIA_TYPE]
            );
        }

        return new JsonResponse(['message' => 'Post deleted successfully'], Response::HTTP_OK);
    }
}