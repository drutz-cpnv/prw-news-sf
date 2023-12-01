<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleForm;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/articles", name: "articles.")]
class ArticleController extends AbstractController
{
    #[Route("", name: "index")]
    public function index(ArticleRepository $repository, Request $request): Response
    {
        return $this->render('articles/index.html.twig', [
            'articles' => $request->query->has('archived') ? $repository->findArchived($request->get('search')) : $repository->findNotArchived($request->get('search')),
        ]);
    }

    #[Route("/new", name: "new", methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleForm::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('articles.index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('articles/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route("/{id}/edit", name: "edit", methods: ['GET', 'PUT'])]
    public function edit(Article $article, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ArticleForm::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('articles.index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('articles/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $repository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $repository->archive($article);
        }

        return $this->redirectToRoute('articles.index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route("/{id}", name: "show")]
    public function show(Article $article): Response
    {
        return $this->render('articles/show.html.twig', [
            'article' => $article
        ]);
    }

}