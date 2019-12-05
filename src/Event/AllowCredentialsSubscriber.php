<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AllowCredentialsSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'addCredentialsToHeaders'
        ];
    }

    public function addCredentialsToHeaders(ResponseEvent $event)
    {
        $event->getResponse()->headers->set('Access-Control-Allow-Credentials', "true");
    }
}
