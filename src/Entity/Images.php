<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: ImagesRepository::class)]
#[Vich\Uploadable]
class Images
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    public ?string $image_title = null;

    #[Vich\UploadableField(mapping: 'front_images', fileNameProperty: 'image_name')]
    // featured_images is the name of the mapping in config/packages/vich_uploader.yaml
    private ?File $imageFile = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageName(): ?string
    {
        return $this->image_name;
    }

    public function setImageName(?string $image_name): self
    {
        $this->image_name = $image_name;

        return $this;
    }

    public function getImageTitle(): ?string
    {
        return $this->image_title;
    }

    public function setImageTitle(string $image_title): self
    {
        $this->image_title = $image_title;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this -> imageFile = $imageFile;

        if (null !== $imageFile) { // if 'imageFile' has been set
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this -> updated_at = new \DateTimeImmutable(); // set 'updatedAt'
        }
    }

    public function getImageFile(): ?File
    {
        return $this -> imageFile;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
