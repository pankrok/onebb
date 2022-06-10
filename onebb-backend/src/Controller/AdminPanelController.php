<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPanelController extends AbstractController
{
    #[Route('%acp_url%{page<.+>}', name: 'app_admin_panel')]
    public function index(): Response
    {
        return $this->render('admin_panel/index.html.twig', [
            'controller_name' => 'AdminPanelController',
        ]);
    }
}
