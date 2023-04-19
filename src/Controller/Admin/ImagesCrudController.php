<?php

namespace App\Controller\Admin;

use App\Entity\Images;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImagesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Images::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            yield DateField::new('updatedAt', 'Date de création')->hideOnForm(), // hideOnForm() is used to hide the field on the new and edit pages
            yield TextField::new('imageFile', 'Taille maximum image : 2MB')->setFormType(VichImageType::class)->onlyOnForms(), // onlyOnForms() is used to display the field only on the new and edit pages
            yield ImageField::new('image_name', 'Apercu')->setBasePath('uploads/front-images')->onlyOnIndex() // onlyOnIndex() is used to display the field only on the index page
        ];
    }

}
