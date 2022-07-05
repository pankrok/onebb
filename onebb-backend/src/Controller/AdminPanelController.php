<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
}
