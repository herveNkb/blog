<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Entity\Articles;
use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this -> render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard ::new()
            -> setTitle('Le blog des Ewoks');
    }

//    Right menu
    public function configureMenuItems(): iterable
    {
        yield MenuItem ::linkToDashboard('Tableau de bord', 'fa fa-dashboard');
        yield MenuItem ::subMenu('Articles', 'fa fa-article') -> setSubItems([
            MenuItem ::linkToCrud('Édition', 'fas fa-book', Articles::class),
            MenuItem ::linkToCrud('Catégories', 'fas fa-list', Categories::class),
        ]);
        yield MenuItem ::linkToCrud('Profil', 'fas fa-users', Users::class);
        yield MenuItem ::linkToUrl('Retour à l\'accueil', 'fas fa-home', $this -> generateUrl('app_main'));

    }

    // Add "consulter" button on admin index page
    public function configureActions(): Actions
    {
        return parent ::configureActions()
            -> add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
