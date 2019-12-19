<?php

namespace App\Event;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JwtPayloadSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'lexik_jwt_authentication.on_jwt_created' => 'addDataToPayload'
        ];
    }

    public function addDataToPayload(JWTCreatedEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        $data['fullName'] = $user->getFullName();
        $data['id'] = $user->getId();
        $data['jobTitle'] = $user->getJob()->getTitle();

        $event->setData($data);
    }
}
