<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\KernelInterface ;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\JsonFileLoader;
use Symfony\Component\Security\Core\Security;
use App\Entity\Plugin;


class PluginService
{
    protected $doctrine;
    protected $trans;
    protected $dir;
    protected $events = [];
    protected $snippets = [];
    protected $plugins = [];
    private $security;

    public function __construct(ManagerRegistry $doctrine, KernelInterface  $kernel, Security $security)
    {
        $this->doctrine = $doctrine; 
        $this->dir = $kernel->getProjectDir() . '/plugin';
        $this->trans = new Translator('pl_PL');
        $this->trans->addLoader('json', new JsonFileLoader());
        $this->security = $security;
    }
    
    // WARNING $all = true only for debug!
    public function loadPlugins($admin = false)
    {   
        $this->plugins = [];
        $this->events = [];
        $this->snippets = [];
        
        $plugins = $this->doctrine->getRepository(Plugin::class);
        if ($admin) {
            $plugins = $plugins->findAll();
        } else {
            $plugins = $plugins->findBy(['active' => 1, 'install' => 1]);
        }
        
        foreach ($plugins as $plugin) {

            $this->snippets[] = $plugin->getSnippet();
            $class = sprintf('\\Plugin\\%s\\%s', $plugin->getName(), $plugin->getName());
            if (class_exists($class) === false) {
                continue;
            }
            
            $pluginInstance = new $class($this->doctrine, $this->trans);
            $this->plugins[$plugin->getName()] = $pluginInstance;
            
            if ($admin && $this->security->isGranted('ROLE_ADMIN') ) {              
                $this->events[$plugin->getName()] = array_merge($pluginInstance->getEvents(), $pluginInstance->getAdminEvents());
            } else {
                $this->events[$plugin->getName()] = $pluginInstance->getEvents();
            }
            
            if (empty($this->plugins[$plugin->getName()]->info()['trans']) === false) {
                $path = $this->dir . DIRECTORY_SEPARATOR . $plugin->getName() . DIRECTORY_SEPARATOR . 'translations/';
                foreach ($this->plugins[$plugin->getName()]->info()['trans'] as $trans) {
                    $this->trans->addResource('json', $path . $trans['domain'] . '.' . $trans['locale'] . '.json', $trans['locale']);
                }
            }
        }
    }
    
    public function checkPluginDir()
    {
        $plugins = array_diff(scandir($this->dir), array('.', '..'));
        foreach ($plugins as $plugin) {
            $this->addPlugin($plugin);
        }
        $this->deleteRemovePlugins($plugins);
    }
    
    protected function addPlugin($name): bool
    {
        $class = sprintf('\\Plugin\\%s\\%s', $name, $name);
        $plugin = $this->doctrine->getRepository(Plugin::class)->findOneBy(['name' => $name]);
        if (class_exists($class) && $plugin === null) {
            
            $pluginInstance = new $class($this->doctrine, $this->trans);
            $info = $pluginInstance ->info();
            $plugin = new Plugin();
            
            $plugin
                ->setName($name)
                ->setVersion($info['version'])
                ->setInstall(0)
                ->setActive(0)
                ->setMeta($info['meta'])
                ->setUpdatedAt(new \DateTimeImmutable('NOW'))
                ->setCreatedAt(new \DateTimeImmutable('NOW'))
                ->setSnippet($pluginInstance->getSnippet())
                ->setEvents($pluginInstance->getEvents())
                ->setAcp($info['acp'])
                ->setIco($info['ico'] ?? null)
            ;
            
            $em = $this->doctrine->getManager();
            $em->persist($plugin);
            $em->flush();
            
            return true;
        }
        return false;
    }
    
    protected function deleteRemovePlugins(array $plugins): void
    {
        $dbPlugins = $this->doctrine->getRepository(Plugin::class)->findAll();
        $em = $this->doctrine->getManager();
        foreach ($dbPlugins as $plugin) {
            if (in_array($plugin->getName(), $plugins) === false) {
                $em->remove($plugin);
            }
        }
        
        $em->flush();
    }
    
    
    public function getSnippets()
    {
        return $this->snippets;
    }
    
    public function dispatch($payload)
    {
       $func = $this->events[$payload['plugin']][$payload['event']];
       
       return $this->plugins[$payload['plugin']]->$func($payload);
    }
    
    public function action($plugin, $action): string|bool
    {        
        return $this->plugins[$plugin]->$action();
    }
}
