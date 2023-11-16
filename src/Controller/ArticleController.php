<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/articles", name: "articles.")]
class ArticleController extends AbstractController
{

    #[Route("", name: "index")]
    public function index(ArticleRepository $repository): Response
    {
        return $this->render('articles/index.html.twig', [
            'articles' => $repository->findAll()
        ]);
    }

}