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

class PostController extends AbstractController
{
    #[Route('/post/new', name: 'app_post_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($this->getUser()->getPets()->first());//TODO: Handle active pet
            
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