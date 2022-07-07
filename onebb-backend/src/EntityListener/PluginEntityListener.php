<?php

namespace App\EntityListener;

use App\Entity\Plugin;
use App\Service\PluginService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PluginEntityListener
{
    protected $pluginService;
    protected $request;
    
    public function __construct(PluginService $pluginService, RequestStack $request)
    {
        $this->pluginService = $pluginService;
        $this->request = $request->getCurrentRequest();
    }
    
    public function postUpdate(Plugin $plugin, LifecycleEventArgs $event)
    {
        $name = $plugin->getName();
        $action = $this->request->toArray();
        
        if (isset($action['install'])) {
            if ($action['install'] === true) {
                    $this->pluginService->action($name, 'install');
            }
                
            if ($action['install'] === false) {
                    $this->pluginService->action($name, 'uninstall');
            }
        }
        
        if (isset($action['active'])) {
            if ($action['active'] === true) {
                    $this->pluginService->action($name, 'active');
            }
            
            if ($action['active'] === false) {
                    $this->pluginService->action($name, 'deactive');
            }
        }
        
        
        
    }
}
