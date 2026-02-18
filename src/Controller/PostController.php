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

class PostController extends AbstractController
{
    public function __construct(
        private string $postsImagesDir,
    ) {
    }
    
    #[Route('/post/new', name: 'app_post_new', methods: ['POST'])]
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
                return $this->render('post/_create_success.stream.html.twig', [
                    'post' => $post,
                    'form' => $this->createForm(PostType::class)->createView()
                ]);
            }

            return $this->redirectToRoute('app_home');
        }

        return $this->render('post/_create.html.twig', [
            'form' => $form,
        ], new Response(422));
    }
}