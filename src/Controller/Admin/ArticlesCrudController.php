<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticlesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Articles::class;
    }


    // Configure the fields to be displayed
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('slug')->hideOnForm(), // hideOnForm() is used to hide the field on the new and edit pages
            TextareaField::new('content')->hideOnIndex(), // hideOnIndex() is used to hide the field on the index page
            DateField::new('createdAt')->hideOnForm(), // hideOnForm() is used to hide the field on the new and edit pages
            TextField::new('featuredImage'),
        ];
    }

    // Modify the ArticlesCrudController globally
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Puts items by creation date ascending
            ->setDefaultSort(['created_at' => 'DESC']);
    }
}
