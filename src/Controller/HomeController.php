<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PostRepository $postRepository): Response
    {

        $form = $this->createForm(PostType::class);
        $posts = $postRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('home/index.html.twig', [
            'form' => $form,
            'posts' => $posts
        ]);

        // return $this->render('home/index.html.twig', [
        //     'controller_name' => 'HomeController',
        // ]);
    }
}
