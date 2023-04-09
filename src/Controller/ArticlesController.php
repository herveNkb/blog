<?php

namespace App\Controller;

use App\Entity\Articles;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/actualites', name: 'actualites_')]
class ArticlesController extends AbstractController
{
    #[Route('/', name: 'app_articles')]
    public function index(ManagerRegistry $doctrine): Response
    {
        // We call the list of all items in the database
        // replaces getDoctrine() which is deprecated
        $articles = $doctrine -> getRepository(Articles::class) -> findAll();

        // We return the view with the list of articles
        return $this -> render('articles/index.html.twig', [
            'articles' => $articles
        ]);
    }
}
