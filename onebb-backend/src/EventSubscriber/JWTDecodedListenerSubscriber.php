<?php

namespace App\EventSubscriber;

use Doctrine\DBAL\Connection;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JWTDecodedListenerSubscriber implements EventSubscriberInterface
{
    private $databaseConnection;

    public function __construct(Connection $databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }

    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $payload = $event->getData();
        $this->databaseConnection->exec(sprintf('DELETE FROM refresh_tokens WHERE username = "%s"', $payload['username']));
    }

    public static function getSubscribedEvents()
    {
        return [
            'lexik_jwt_authentication.on_jwt_created' => 'onJWTCreated',
        ];
    }
}
