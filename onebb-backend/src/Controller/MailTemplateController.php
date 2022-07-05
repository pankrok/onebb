<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

class MailTemplateController extends AbstractController
{
    /**
     * Require ROLE_CONFIG_GET only for this action.
     *
     * @IsGranted("ROLE_CONFIG_GET")
     */
    #[Route('/api/email-template/{template}', methods: 'GET')]
    public function getConfig(string $template): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        
        $path = ($this->getParameter('kernel.project_dir'). DIRECTORY_SEPARATOR . $this->getParameter('mail_dir') . $template . '.html.twig');
        
        if (file_exists($path) === true) {
            $content = file_get_contents($path);
            $response->setContent(json_encode(['template' => $content]));
        } 
        
        if (file_exists($path) === false) {
            $response->setContent(json_encode(['error' => 'template not found']));
            $response->setStatusCode(404);
        }

        return $response;
    }

    /**
     * Require ROLE_CONFIG_PUT only for this action.
     *
     * @IsGranted("ROLE_CONFIG_PUT")
     */
    #[Route('/api/email-template/{template}', methods: 'PUT')]
    public function putConfig(Request $request, string $template): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        
        $path = ($this->getParameter('kernel.project_dir'). DIRECTORY_SEPARATOR . $this->getParameter('mail_dir') . $template . '.html.twig');
        $data = $request->toArray();
        if (file_exists($path) === true) {
            $content = file_put_contents($path, $data['template']);
            $response->setContent(json_encode(['success' => true]));
        } 
        
        if (file_exists($path) === false) {
            $response->setContent(json_encode(['error' => 'template not found']));
            $response->setStatusCode(404);
        }

        return $response;
    }
}
