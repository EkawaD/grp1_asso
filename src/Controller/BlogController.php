<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog')]
    public function index(): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/blog/new', name: 'new_article')]
    public function newArticle(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $article = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($article);
            $em->flush();
            
            return $this->redirectToRoute("article", [
                "id" => $article->getId(),
            ]);
        }


        return $this->render('blog/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/blog/article/{id}', name: 'article')]
    public function article ($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        return $this->render("blog/single.html.twig", [
            "article" => $article
        ]);
    }


}