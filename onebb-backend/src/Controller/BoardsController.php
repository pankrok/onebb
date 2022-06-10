<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class BoardsController extends AbstractController
{
    private $security;
     
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    #[Route('/', name: 'home')]
    #[Route('/{route}', name: 'vue', requirements: ['route' => '^(?!api)(?!%acp_url%).+'])]
    public function index(): Response
    {
        if ($this->getParameter('maintaince') === true && !$this->security->isGranted('ROLE_ADMIN')) {
            return $this->render('maintaince.html.twig');    
        }
        
        // FIXME here will be seo bot checker to delivery static content generate by TWIG!
        
        return $this->render('boards/index.html.twig', []);
    }
}
