<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    private $databaseConnection;

    public function __construct(Connection $databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }

    #[Route('/api/logout', name: 'logout')]
    public function logout(Request $request): JsonResponse
    {
        if (isset($_COOKIE['refresh_token'])) {
            $this->databaseConnection->exec(sprintf('
                DELETE FROM refresh_tokens
                WHERE refresh_token = "%s"
            ', $_COOKIE['refresh_token']));
            $response = new JsonResponse(['logged_out' => true]);
            $cookie = Cookie::create('refresh_token')
            ->withValue(null)
            ->withExpires(new \DateTime())
            ->withSecure(true)
            ->withHttpOnly(true);
            $response->headers->setCookie($cookie);

            return $response;
        }

        return new JsonResponse(['logged_out' => false]);
    }
}
