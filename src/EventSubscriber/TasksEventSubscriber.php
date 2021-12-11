<?php

namespace App\EventSubscriber;

use App\Entity\Tasks;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TasksEventSubscriber implements EventSubscriberInterface
{
    private $security;
    private $userRepository;

    public function __construct(
        Security $security,
        UserRepository $userRepository
    ) {
        $this->security = $security;
        $this->userRepository = $userRepository;
    }
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['createdTask'],
            BeforeEntityUpdatedEvent::class => ['updatedTask'],
        ];
    }

    public function createdTask(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Tasks)
        {
            $entity->setCreatedAt(new \DateTime());
            $entity->setCreatedBy($this->userRepository->find($this->security->getUser()));
            $entity->setUpdatedAt(new \DateTime());
            $entity->setUpdatedBy($this->userRepository->find($this->security->getUser()));
        }
    }

    public function updatedTask(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Tasks)
        {
            $entity->setUpdatedAt(new \DateTime());
            $entity->setUpdatedBy($this->userRepository->find($this->security->getUser()));
        }
    }


}