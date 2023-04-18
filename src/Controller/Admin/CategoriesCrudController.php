<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoriesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Categories::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            Yield TextField::new('name', 'Nom de la catégorie'),
            Yield TextField::new('slug', 'Slug')->hideOnForm(), // hideOnForm() is used to hide the field on the new and edit pages

        ];
    }

}
