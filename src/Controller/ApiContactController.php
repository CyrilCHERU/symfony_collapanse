<?php

namespace App\Controller;

use App\Event\ContactMailEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiContactController extends AbstractController
{
    /**
     * @Route("/api/contact", name="api_contact", methods={"POST"})
     */
    public function contact(Request $request, SerializerInterface $serializer, EventDispatcherInterface $ed)
    {
        $data = $request->getContent();

        $contact = $serializer->deserialize($data, 'App\Entity\Contact', 'json');

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
