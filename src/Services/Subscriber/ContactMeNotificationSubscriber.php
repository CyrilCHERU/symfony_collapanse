<?php

namespace App\Services\Subscriber;

use App\Event\ContactMailEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactMeNotificationSubscriber implements EventSubscriberInterface
{
    protected $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            'site.collapanse.contact' => 'sendNotificationEmail'
        ];
    }

    public function sendNotification(ContactMailEvent $event)
    {
        $contact = $event->getContact();

        $email = new Email();
        $email->from($contact['email'])
            ->to('admin@admin.com')
            ->subject('Collapanse, quelqu un tente de prendre contact via le site')
            ->html("
            <h2>Une personne du nom de : {$contact['name']}, prend contact avec vous via votre site Collapanse.</h2
            <strong>Voici son message :</strong>
            <p>{$contact['message']}</p>
            ");

        $this->mailer->send($email);
    }
}
