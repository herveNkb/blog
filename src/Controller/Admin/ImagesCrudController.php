<?php

namespace App\Controller\Admin;

use App\Entity\Images;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
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
            yield TextField ::new('image_title', 'Titre de l\'image'),
            yield DateField ::new('updatedAt', 'Date de création')
                -> hideOnForm(), // hideOnForm() is used to hide the field on the new and edit pages
            yield TextField ::new('imageFile', 'Taille maximum image : 2MB')
                -> setFormType(VichImageType::class)
                -> onlyOnForms() // onlyOnForms() is used to display the field only on the new and edit pages
                ->setFormTypeOptions([ // this option allows you to add a file input field with the accept="image/*" attribute
                    'constraints' => [
                        new Callback([
                            'callback' => [$this, 'validateImageMimeTypeSizeWidthHeight'],
                        ])
                    ]
                ]),
            yield ImageField ::new('image_name', 'Apercu')
                -> setBasePath('uploads/front-images')
                -> onlyOnIndex() // onlyOnIndex() is used to display the field only on the index page
        ];
    }

    // This method is used to validate the MIME type, file size and image dimensions.
    public function validateImageMimeTypeSizeWidthHeight(?UploadedFile $file, ExecutionContextInterface $context): void
    {
        // If no file is uploaded, do not validate anything
        if ($file === null) {
            return;
        }

        // Define the allowed MIME types, maximum file size and image dimensions
        $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $maxSize = 200 * 1024; // 200 Ko
        $maxWidth = 500;
        $maxHeight = 500;

        // Validate MIME type
        $fileMimeType = $file->getMimeType(); // getMimeType() is a method of the UploadedFile class
        // If the MIME type is not in the array of allowed types, add a violation
        if (!in_array($fileMimeType, $allowedMimeTypes, true)) {
            $context->buildViolation('Format d\'image invalide, types de formats autorisés : .jpeg  .jpg  .png  .gif')
                ->setParameter('{{ types }}', implode(', ', $allowedMimeTypes)) // {{ types }} is replaced by the list of allowed MIME types
                ->addViolation(); // This method adds a violation with the message above
        }

        // Validate file size
        $fileSize = $file->getSize(); // getSize() is a method of the UploadedFile class
        // If the file size is greater than the maximum allowed size, add a violation
        if ($fileSize > $maxSize) {
            $context->buildViolation('L\'image est trop lourde. Poids maximum: 200 Ko.')
                ->setParameter('{{ size }}', $maxSize)
                ->addViolation();
        }

        // Validate image dimensions
        [$width, $height] = getimagesize($file->getPathname()); // getimagesize() is a PHP function that returns an array containing the width and height of the image
        // If the image dimensions are greater than the maximum allowed dimensions, add a violation
        if ($width > $maxWidth || $height > $maxHeight) {
            $context->buildViolation('Les dimensions de l\'image sont trop grandes. Dimensions maximales : {{ width }}x{{ height }} px.')
                ->setParameter('{{ width }}', $maxWidth)
                ->setParameter('{{ height }}', $maxHeight)
                ->addViolation();
        }
    }

}
