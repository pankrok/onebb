<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\OneMessenger;
use App\Entity\User;

class TestController extends AbstractController
{
   
    
    #[Route('/api/test1', name: 'apitest1')]
    public function index(ManagerRegistry $em): Response
    {     
       $u1 = $em->getRepository(User::class)->find(3);
       $u2 = $em->getRepository(User::class)->find(4);
       
       $test = $em->getRepository(OneMessenger::class)->isExist([$u1, $u2]);
        var_dump($test->getId());
    
       return new Response();
    }
    
}
