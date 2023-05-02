<?php

namespace App\Controller;


use App\Entity\Images;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ManagerRegistry $doctrine): Response
    {
        // Retrieve data from database
        // Replaces getDoctrine() which is deprecated
        $images = $doctrine -> getRepository(Images::class) -> findAll();
        return $this->render('main/index.html.twig', [
            'images' => $images
        ]);
    }
}
