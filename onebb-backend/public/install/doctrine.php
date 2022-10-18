<?php

namespace Install\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;
use App\Entity\User;
use App\Entity\Group;
use ApiPlatform\Core\Annotation\ApiResource;

class Doctrine {
 
    protected $entityManager;
 
    public function __construct() 
    {
        $config = new Configuration();
        $config->setProxyDir(realpath(__DIR__) . '/../var/cache/prod/doctrine/orm/Proxies');
        $config->setProxyNamespace('Proxies');
         
        $paths = [
            'App\\' => realpath(__DIR__) . "/../src/",
        ];
         
        // Tells Doctrine what mode we want
        $isDevMode = true;
         
        // Doctrine connection configuration
        $dbParams = array(
            'url' => ENV['DATABASE_URL']
        );
         
        $driverImpl = $config->newDefaultAnnotationDriver($paths, false);
        $config->setMetadataDriverImpl($driverImpl);
        $this->entityManager = EntityManager::create($dbParams, $config);
    }
    
    public function getManager() 
    {
        return $this->entityManager;
    }

}