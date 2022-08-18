<?php

namespace App\Controller\Plugin;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\Metafield;
use App\Entity\MetafieldValue;
use App\Entity\Box;
use App\Entity\Skin;
use App\Entity\SkinBoxes;
use App\Entity\Plugin; 

class PluginController
{
    private $doctrine;
    protected $translator;
    protected $plugin;

    public function __construct(ManagerRegistry $doctrine, TranslatorInterface $trans)
    {
        $this->doctrine = $doctrine;
        $this->translator = $trans;
        $this->plugin = $this->doctrine->getRepository(Plugin::class)->findOneBy(['name' => $this->info()['name']]);
    }
    
    protected function createMetafield(string $namespace, string $key): Metafield
    {
        $metafield = $this->doctrine->getRepository(Metafield::class)->findOneBy(['namespace' => $namespace, 'metafield_key' => $key]);
        
        if ($metafield === null ) {    
            $em = $this->doctrine->getManager();
            $metafield = new Metafield();
            $metafield
                ->setNamespace($namespace)
                ->setMetafieldKey($key)
                ->setPlugin($this->plugin)
            ;
            
            $em->persist($metafield);
            $em->flush();
        }
        
        return $metafield;
    }
    
    protected function createMetafieldValue(Metafield $metafield, array $value): ?MetafieldValue
    {
        $metafieldValue = $this->doctrine->getRepository(MetafieldValue::class)->findOneBy(['metafield' => $metafield, 'value' => $value]);
        
        if ($metafieldValue !== null ) {   
            return false;
        }
        
        $em = $this->doctrine->getManager();
        $metafieldValue = new MetafieldValue();
        $metafieldValue
            ->setMetafield($metafield)
            ->setValue($value)
        ;
        
        $em->persist($metafieldValue);
        $em->flush();
        
        return $metafieldValue;
    }
    
    protected function getMetafield(string $namespace, string $key): ?Metafield
    {
        return $this->doctrine->getRepository(Metafield::class)->findOneBy(['namespace' => $namespace, 'metafield_key' => $key]);
    }
    
    protected function getMetafieldValues(string $namespace, string $key)
    {
        $metafield = $this->getMetafield($namespace, $key);
        if ($metafield !== null) {
            return $metafield->getMetafieldValues();
        }
        
        return null;
    }
    
    protected function getMetafieldValue(int $id)
    {        
        $mv = $this->doctrine->getRepository(MetafieldValue::class)->find($id);
        if ($mv === null) {
            return null;
        }
        
        return $mv->getValue();
    }
    
    protected function updateMetafieldValue(int $id, array $value): bool
    {
        $metafieldValue = $this->doctrine->getRepository(Metafield::class)->find($id);
        $metafieldValue->setValue($value);
        $em = $this->doctrine->getManager();
        $em->persist($metafieldValue);
        $em->flush();
        
        return true;
    }
    
    protected function deleteMetafield(string $namespace, string $key): bool
    {
        $metafield = $this->doctrine->getRepository(Metafield::class)->findOneBy(['namespace' => $namespace, 'metafield_key' => $key]);
        if ($metafield !== null ) {   
            $em = $this->doctrine->getManager();
            $em->remove($metafield);
            $em->flush();
            
            return true;
        }
        
        return false;
    }
    
    protected function deleteMetafieldValue(int $id): bool
    {
        $metafieldValue = $this->doctrine->getRepository(MetafieldValue::class)->find($id);
        if ($metafieldValue !== null ) {  
            $em = $this->doctrine->getManager();
            $em->remove($metafieldValue);
            $em->flush();
        
            return true;
        }
        
         return false;
    }
    
    protected function createBox(string $name, string $content): ?Box
    {
        $em = $this->doctrine->getManager();
        $box = new Box();
        $box
            ->setName($name)
            ->setHtml($content)
            ->setEngine('PluginBox')
            ->setUpdatedAt(new \DateTimeImmutable('NOW'))
            ->setCreatedAt(new \DateTimeImmutable('NOW'))
        ;
           
        $em->persist($box);
        $em->flush();
        
        return $box;
    }
    
    protected function editBox(int $id, string $content): bool
    {
        $box = $this->doctrine->getRepository(Box::class)->find($id);
        if ($box !== null ) {   
            $em = $this->doctrine->getManager();
            $box->setHtml($content);
            $em->persist($box);
            $em->flush();
            
            return true;
        }
        
        return false;      
    }
    
    protected function setBoxOnSkin(Box $box, string $page, string $position): SkinBoxes
    {
        $skin = $this->doctrine->getRepository(Skin::class)->findOneBy(['active' => 1]);
        $skinBox = new SkinBoxes();
        $skinBox
            ->setSkin($skin)
            ->setBox($box)
            ->setPage($page)
            ->setPosition($position)
            ->setActive(false)
        ;
        
        $em = $this->doctrine->getManager();
        $em->persist($skinBox);
        $em->flush();
        
        return $skinBox;
    }
    
    protected function activeSkinBox($id)
    {
        $skinBox = $this->doctrine->getRepository(SkinBoxes::class)->find($id);
        if ($skinBox) {
            $skinBox->setActive(true);
            $em = $this->doctrine->getManager();
            $em->persist($skinBox);
            $em->flush();
        }
    }
    
    protected function deactiveSkinBox($id)
    {
        $skinBox = $this->doctrine->getRepository(SkinBoxes::class)->find($id);
        if ($skinBox) {
            $skinBox->setActive(false);
            $em = $this->doctrine->getManager();
            $em->persist($skinBox);
            $em->flush();
        }
    }
    
    
    protected function deleteBox(int $id): bool
    {
        $box = $this->doctrine->getRepository(Box::class)->find($id);
        if ($box !== null && $box->getEngine() === 'PluginBox') { 
            $em = $this->doctrine->getManager();
            $skinBoxes = $this->doctrine->getRepository(SkinBoxes::class)->findBy(['box' => $box]) ?? [];
            foreach ($skinBoxes as $skinBox) {
                $em->remove($skinBox);
            }
           
            $em->remove($box);
            $em->flush();
            return true;
        }
        
        return false;    
    }
    
}
