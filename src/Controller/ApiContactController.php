<?php

namespace App\Controller;

use App\Event\ContactMailEvent;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ApiContactController extends AbstractController
{
    /**
     * @Route("/api/contact", name="api_contact", methods={"POST"})
     */
    public function contact($data, SerializerInterface $serializer, EventDispatcherInterface $ed)
    {
        $contact = $serializer->deserialize($data, , json);
        
        $contactMailEvent = new ContactMailEvent($contact);

        $ed->dispatch($contactMailEvent, 'site.collapanse.contact');

        $json = $serializer->serialize(
            [
                'message' => "Merci pour votre message, nous allons y répondre dans les plus brefs délais !"
            ],
            'json'
        );

        return new JsonResponse($json, 200, [], true);
    }
}
