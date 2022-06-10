<?php

namespace App\Controller;

use App\Security\UserValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ValidationController extends AbstractController
{
    #[Route('/api/validation', name: 'app_validation')]
    public function index(Request $request, UserValidator $userValidator): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode([
                'success' => false,
        ]));
        if ('POST' !== $request->getMethod()) {
            return $response->setStatusCode(405);
        }

        if ('application/json' === $request->headers->get('content-type') || 'application/ld+json' === $request->headers->get('content-type')) {
            $json = json_decode($request->getContent());

            $confirmed = $userValidator->handleEmailConfirmation($json->hash);
            if (true === $confirmed) {
                $response->setStatusCode(200);
            } else {
                $response->setStatusCode(403);
            }

            $response->setContent(json_encode([
                'success' => $confirmed,
            ]));
        } else {
            $response->setStatusCode(415);
        }

        return $response;
    }
}
