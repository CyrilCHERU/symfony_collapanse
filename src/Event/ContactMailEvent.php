<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ContactMailEvent extends Event
{

    protected $contact;

    public function __construct($contact)
    {
        $this->contact = $contact;
    }

    public function getContact()
    {
        return $this->contact;
    }
}
