<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleForm;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route("/new", name: "new", methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleForm::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($form);
        }

        return $this->render('articles/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route("/{id}", name: "show")]
    public function show(Article $article): Response
    {
        return $this->render('articles/show.html.twig', [
            'article' => $article
        ]);
    }

}