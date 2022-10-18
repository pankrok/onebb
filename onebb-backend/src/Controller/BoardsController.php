<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Service\PluginService;

class BoardsController extends AbstractController
{
    private $security;
     
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    #[Route('/', name: 'home')]
    #[Route('/{route}', name: 'vue', requirements: ['route' => '^(?!api)(?!%acp_url%)(?!wizard).+'])]
    public function index(PluginService $pluginService, Request $request): Response
    {     
        if (str_contains($request->getUri(), '.css') || str_contains($request->getUri(), '.js')) {
            return new Response(200);
        }
        
        if ($this->getParameter('maintaince') === true && !$this->security->isGranted('ROLE_ADMIN')) {
            return $this->render('maintaince.html.twig');    
        }
        
        $params = [
            'snippets' => $pluginService->getSnippets(), 
            'board_name' => $this->getParameter('board_name'), 
            'meta_descripton' => $this->getParameter('meta.description'),
        ];
        
        return $this->render('boards/index.html.twig', $params);
    }
    
    #[Route('/api/plugin/dispatch', name: 'plugin', methods: 'POST')]
    public function dispatchPlugin(PluginService $pluginService, Request $request): Response
    {     
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        
        $payload = $request->toArray();
        $return = $pluginService->dispatch($payload);
        
        $response->setContent(json_encode($return));
        
        return $response;
        
    }
}
