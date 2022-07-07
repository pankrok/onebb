<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PluginService;

class AdminPanelController extends AbstractController
{
    
    #[Route('%acp_url%{page<.+>}', name: 'app_admin_panel')]
    public function index(PluginService $pluginService): Response
    {
        $pluginService->checkPluginDir();
        return $this->render('admin_panel/index.html.twig', [
            'controller_name' => 'AdminPanelController',
        ]);
    }
    
    #[Route('api/plugin/control', name: 'app_plugin_control')]
    /**
     * Require ROLE_ADMIN only for this action
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminPlugin(Request $request, PluginService $pluginService): Response
    {
        $payload = $request->toArray();
        if ($payload['temp'] === '' || $payload['temp'] === null) {
            $payload['temp'] = 'getAdminTemplate';
        }   

        if ($payload['script'] === '' || $payload['script'] === null) {
            $payload['script'] = 'getAdminScript';
        }  
            
        $response = new Response();
        $response->setContent(json_encode([
            'template' => $pluginService->action($payload['plugin'], $payload['temp']),
            'script' => $pluginService->action($payload['plugin'],  $payload['script'])
        ]));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
}
