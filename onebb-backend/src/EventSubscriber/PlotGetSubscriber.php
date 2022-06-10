<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Plot;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class PlotGetSubscriber implements EventSubscriberInterface
{
    private $em;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['incrementView', EventPriorities::POST_WRITE],
        ];
    }

    public function incrementView(ViewEvent $event): void
    {
        $plot = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$plot instanceof Plot || Request::METHOD_GET !== $method) {
            return;
        }

        $plot->incrementViews();
        $this->em->persist($plot);
        $this->em->flush();
    }
}
