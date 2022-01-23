<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    private $passwordHassher;
    private $entityManager;

    public function __construct(EntityManagerInterface $entity_manager, UserPasswordHasherInterface $password_hasher)
    {
        $this->passwordHassher = $password_hasher;
        $this->entityManager = $entity_manager;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityUpdatedEvent::class => 'updatePass'
        ];
    }

    public function updatePass(BeforeEntityUpdatedEvent $event) {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }

        $entity->setPassword(
            $this->passwordHassher->hashPassword($entity, $entity->getPassword())
        );
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}