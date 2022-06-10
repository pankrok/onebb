<?php

namespace App\Service;

use Gesdinet\JWTRefreshTokenBundle\Service\RefreshToken;
use Symfony\Component\HttpFoundation\Request;

class RefreshService
{
    /** @var RefreshToken */
    private $refreshToken;

    public function __construct(RefreshToken $refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    public function refresh(Request $request)
    {
        if ($bearer = $request->cookies->get('refresh_token')) {
            $request->initialize(
                $request->query->all(),
                $request->request->all(),
                $request->attributes->all(),
                $request->cookies->all(),
                $request->files->all(),
                $request->server->all(),
                json_encode(['refresh_token' => $bearer])
            );
        }

        return $this->refreshToken->refresh($request);
    }
}
