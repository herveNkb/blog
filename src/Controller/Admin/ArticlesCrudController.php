<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use App\Controller\Admin\ImagesCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
            yield TextField ::new('title', 'Titre'),
            yield TextField ::new('slug', 'Slug') -> hideOnForm(), // hideOnForm() is used to hide the field on the new and edit pages
            yield TextEditorField ::new('content', 'Article')
                -> hideOnIndex() -> setNumOfRows(20), // hideOnIndex() is used to hide the field on the index page
            yield DateField ::new('createdAt', 'Date de création')
                -> hideOnForm(), // hideOnForm() is used to hide the field on the new and edit pages
            yield TextField ::new('imageFile', 'Taille maximum image : 2MB')
                -> setFormType(VichImageType::class) -> onlyOnForms()  // onlyOnForms() is used to display the field only on the new and edit pages
                -> setFormTypeOptions([ // this option allows you to add a file input field with the accept="image/*" attribute
                    'constraints' => [
                        new Callback([
                            'callback' =>  function (?UploadedFile $file, ExecutionContextInterface $context) {
                                // Create an instance of the ImagesCrudController class
                                $imagesCrudController = new ImagesCrudController();
//                              // Call the validateImageMimeTypeSizeWidthHeight() method of the ImagesCrudController class
                                $imagesCrudController->validateImageMimeTypeSizeWidthHeight($file, $context);
                            },
                        ])
                    ]
                ]),
            yield ImageField ::new('featured_image', 'Apercu')
                -> setBasePath('uploads/images')
                -> onlyOnIndex(), // onlyOnIndex() is used to display the field only on the index page
            yield AssociationField ::new('categories', 'Catégorie') // AssociationField is used to display a field that is a relation to another entity
            -> onlyOnIndex() // onlyOnIndex() is used to display the field only on the index page
            -> hideOnDetail()
            -> hideOnForm()// hideOnDetail() is used to hide the field on the detail page,
        ];
    }

    // Modify the ArticlesCrudController globally
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Puts items by creation date ascending
            -> setDefaultSort(['created_at' => 'DESC']);
    }
}
