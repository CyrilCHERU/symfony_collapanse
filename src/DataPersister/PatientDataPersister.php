<?php

namespace App\DataPersister;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Patient;

class PatientDataPersister implements ContextAwareDataPersisterInterface
{
    protected $em;
    protected $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Patient && $data->getId() == null;
    }

    /**
     * Fonction qui ajoute l'utilisateur connecté à la création du patient
     *
     * @param Patient $data
     * @param array $context
     * @return void
     */
    public function persist($data, array $context = [])
    {
        // Liaison du Patient au User connecté
        $user = $this->security->getUser();
        if ($user->getJobTitle() == "Docteur") {
            $data->setDoctor($user);
        } else {
            $data->addNurse($this->security->getUser());
        };


        // enregistrement
        $this->em->persist($data);
        $this->em->flush();

        return $data;
    }

    public function remove($data, array $context = [])
    {
        // 1. remove et flush
        $this->em->remove($data);
        $this->em->flush();

        return $data;
    }
}
