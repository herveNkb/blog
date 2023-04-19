<?php

namespace App\EventSubscriber;

use App\Entity\Images;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class EasyAdminFrontImagesSubscriber implements EventSubscriberInterface
{
    private Security $security;

// Security is a service that allows to get the current user
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    // This method is called a event when an entity is persisted
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['setImageFrontAndDate'],
        ];
    }

    // We retrieve the instance of the Images entity
    public function setImageFrontAndDate(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        // We check that the entity is indeed an instance of Images
        if (!($entity instanceof Images)) {
            return;
        }
        // We retrieve the current user
        $user = $this->security->getUser();
        // We send the user to the entity
        $entity->setUser($user);
    }
}