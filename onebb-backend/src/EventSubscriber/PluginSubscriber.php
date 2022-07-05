<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent ;
use App\Service\PluginService;
use App\Entity\Plugin;

class PluginSubscriber implements EventSubscriberInterface
{
    protected $pluginService;
    
    public function __construct(PluginService $pluginService)
    {
        $this->pluginService = $pluginService;
    }
    
    public function loadPlugins(RequestEvent $event)
    {
        if ($event->isMainRequest() === true) {
            $this->pluginService->loadPlugins(boolval($event->getRequest()->headers->get('X-ONEBB-ADMIN')));
        }
        
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'loadPlugins',
        ];
    }
}
