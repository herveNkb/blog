<?php

namespace App\Controller;

use App\Entity\Articles;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/actualites', name: 'actualites_')]
class ArticlesController extends AbstractController
{
    #[Route('/', name: 'app_articles')]
    public function index(ManagerRegistry $doctrine, Request $request, PaginatorInterface $paginator): Response
    {
        // findBy() method that retrieves data from the articles table with a descending date sort.
        // Replaces getDoctrine() which is deprecated
        $data = $doctrine -> getRepository(Articles::class) -> findBy([],
        ['created_at' => 'DESC']);

        // We paginate the data
        $articles = $paginator -> paginate(
            $data, // Data to paginate
            $request -> query -> getInt('page', 1), // Current page number, 1 by default
            1 // Number of items per page
        );



        // We return the view with the list of articles
        return $this -> render('articles/index.html.twig', [
            'articles' => $articles
        ]);
    }
}
