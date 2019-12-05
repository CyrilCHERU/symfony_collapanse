<?php

namespace App\DataPersister;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPasswordDataPersister implements ContextAwareDataPersisterInterface
{
    protected $em;
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    /**
     * Sommes-nous dans le cas de la création d'un User ?
     *
     * @param [type] $data
     * @param array $context
     * @return boolean
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof User && $data->getId() == null;
    }

    /**
     * Encodage du mot de passe avant enregistrement en BDD
     *
     * @param User $data
     * @param array $context
     * @return void
     */
    public function persist($data, array $context = [])
    {
        // Récupérer le password pour encodage
        $plainPassword = $data->getPassword();
        $password = $this->encoder->encodePassword($data, $plainPassword);
        // Assignation du mot de passe crypté
        $data->setPassword($password);
        // Enregistrement en BDD
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
