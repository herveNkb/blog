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

//    Method that allows to display the slug of the article
    #[Route('/{slug}', name: 'article')]
    public function article($slug, ManagerRegistry $doctrine): Response
    {
        // We retrieve the article with the slug
        $article = $doctrine -> getRepository(Articles::class) -> findOneBy([
            'slug' => $slug
        ]);
        // We check if an article is found?
        if (!$article) {
            // If not, we return a 404 error
            throw $this -> createNotFoundException('L\'article n\'a pas été trouvé');
        }
        // If an article is found, we return the view with the article

        return $this -> render('articles/article.html.twig', [
            'article' => $article
        ]);
    }
}
