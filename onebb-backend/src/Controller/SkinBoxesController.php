<?php

namespace App\Controller;

use App\Entity\SkinBoxes;
use App\Entity\Skin;
use App\Entity\Box;
use App\Repository\SkinBoxesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SkinBoxesController extends AbstractController
{
    protected $managerRepository;
    protected $entityManager;
    protected $skinBoxesRepository;
    
    public function __construct(ManagerRegistry $managerRepository, SkinBoxesRepository $skinBoxesRepository) 
    {
        $this->managerRepository = $managerRepository;
        $this->entityManager = $managerRepository->getManager();
        $this->skinBoxesRepository = $skinBoxesRepository;
    }
    
    // FIXME this will be only for admins!
    #[Route('/api/skin_boxes/bulk', methods: 'POST')]
    public function bulk(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        
        $data = $request->toArray();
        foreach ($data['modules'] as $key => $moduleData) {
            foreach($moduleData as $box) {
                $entity = $this->skinBoxesRepository->findOneBy([
                    'skin' => $data['id'], 
                    'box' => $box['id'],
                    'page' => $data['page']
                ]) ?? new SkinBoxes();
                
                if ($key === 'unused') {
                    $this->entityManager->remove($entity);
                } 
                
                if ($key !== 'unused') {
                    $skinEntiry = $this->managerRepository->getRepository(Skin::class)->find($data['id']);
                    $boxEntity = $this->managerRepository->getRepository(Box::class)->find($box['id']);
                    $entity
                        ->setSkin($skinEntiry)
                        ->setBox($boxEntity)
                        ->setPage($data['page'])
                        ->setPosition($key)
                        ->setActive(1)
                    ;
                    $this->entityManager->persist($entity);
                } 
                
                $this->entityManager->flush();
            } 
        }
        
        $response->setContent(json_encode([
            'success' => true // FIXME that shoud return true or false after change boxes in DB!
        ]));
        
        return $response;
    }
}