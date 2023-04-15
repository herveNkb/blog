<?php

namespace App\EventSubscriber;

use App\Entity\Articles;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private SluggerInterface $slugger;
    private Security $security;

// SluggerInterface is a service that allows to slug a string
// Security is a service that allows to get the current user
    public function __construct(SluggerInterface $slugger, Security $security)
    {
        $this->slugger = $slugger;
        $this->security = $security;
    }

    // This method is called a event when an entity is persisted
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['setArticlesUserAndSlugAndDate'],
        ];
    }

    // We retrieve the instance of the Articles entity
    public function setArticlesUserAndSlugAndDate(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        // We check that the entity is indeed an instance of Articles
        if (!($entity instanceof Articles)) {
            return;
        }

        // We retrieve the title, transform it into a slug, and temporarily store it in the slug variable
        $slug = $this->slugger->slug($entity->getTitle());
        // We send the slug to the entity
        $entity->setSlug($slug);

        // Generation of creation date and update date
        $now = new DateTime('now');
        // We send the date to the entity
        $entity->setCreatedAt($now);
        $entity->setUpdatedAt($now);

        // We retrieve the current user
        $user = $this->security->getUser();
        // We send the user to the entity
        $entity->setUsers($user);

    }
}